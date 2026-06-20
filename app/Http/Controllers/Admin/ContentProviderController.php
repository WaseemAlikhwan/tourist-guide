<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentProviderApplication;
use App\Models\User;
use App\Notifications\ContentProviderApplicationReviewed;
use App\Services\ProviderEarningsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentProviderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $applicationsQuery = ContentProviderApplication::with(['user'])
            ->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $applicationsQuery->where('status', $status);
        }

        $applications = $applicationsQuery->paginate(15);

        $stats = [
            'pending' => ContentProviderApplication::where('status', 'pending')->count(),
            'approved' => ContentProviderApplication::where('status', 'approved')->count(),
            'rejected' => ContentProviderApplication::where('status', 'rejected')->count(),
        ];

        $reviewedTotal = ContentProviderApplication::whereIn('status', ['approved', 'rejected'])->count();
        $kpis = [
            'reviewed_today' => ContentProviderApplication::whereDate('reviewed_at', today())->count(),
            'oldest_pending_days' => optional(
                ContentProviderApplication::where('status', 'pending')->oldest('created_at')->first()
            )?->created_at?->diffInDays(now()) ?? 0,
            'rejection_ratio' => $reviewedTotal > 0
                ? round(($stats['rejected'] / $reviewedTotal) * 100, 1)
                : 0.0,
        ];

        return view('admin.content-providers.index', compact('applications', 'status', 'stats', 'kpis'));
    }

    public function show(ContentProviderApplication $application)
    {
        $application->load(['user', 'admin']);

        return view('admin.content-providers.show', compact('application'));
    }

    public function approve(Request $request, ContentProviderApplication $application)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($application->status === 'approved') {
            return redirect()
                ->route('admin.content-providers.show', $application)
                ->with('info', 'تم اعتماد هذا الطلب مسبقاً.');
        }

        if ($application->status === 'rejected') {
            return redirect()
                ->route('admin.content-providers.show', $application)
                ->with('info', 'تم رفض هذا الطلب مسبقاً. أنشئ طلباً جديداً للمراجعة.');
        }

        $admin = Auth::guard('admin')->user();

        $application->update([
            'status' => 'approved',
            'admin_id' => $admin?->id,
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_at' => now(),
        ]);

        /** @var User $user */
        $user = $application->user;

        if ($user) {
            $user->update([
                'is_content_provider' => true,
                'content_provider_status' => 'approved',
                'can_login' => true,
            ]);
            $user->notify(new ContentProviderApplicationReviewed($application));
        }

        return redirect()
            ->route('admin.content-providers.show', $application)
            ->with('success', 'تم اعتماد مزوّد المحتوى بنجاح وتم تفعيل إمكانية تسجيل الدخول له كمزوّد محتوى معتمد.');
    }

    public function reject(Request $request, ContentProviderApplication $application)
    {
        $request->validate([
            'admin_notes' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        if ($application->status === 'rejected') {
            return redirect()
                ->route('admin.content-providers.show', $application)
                ->with('info', 'تم رفض هذا الطلب مسبقاً.');
        }

        $admin = Auth::guard('admin')->user();

        $application->update([
            'status' => 'rejected',
            'admin_id' => $admin?->id,
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_at' => now(),
        ]);

        /** @var User $user */
        $user = $application->user;

        if ($user) {
            $user->update([
                'is_content_provider' => false,
                'content_provider_status' => 'rejected',
                'can_login' => false,
            ]);
            $user->notify(new ContentProviderApplicationReviewed($application));
        }

        return redirect()
            ->route('admin.content-providers.show', $application)
            ->with('success', 'تم رفض الطلب/إلغاء تفعيل المزوّد وتحديث حالة الحساب.');
    }

    public function viewDocument(ContentProviderApplication $application, string $document)
    {
        $path = match ($document) {
            'commercial-registration' => $application->commercial_registration_path,
            'tourism-license' => $application->tourism_license_path,
            'ownership-document' => $application->ownership_document_path,
            default => null,
        };

        abort_if(!$path, 404, 'المستند غير مرفق لهذا الطلب.');
        abort_unless(Storage::disk('public')->exists($path), 404, 'المستند غير موجود على الخادم.');

        return response()->file(Storage::disk('public')->path($path));
    }

    public function showProvider(User $user, ProviderEarningsService $earnings): View
    {
        abort_unless($user->is_content_provider, 404);

        $user->load(['latestContentProviderApplication']);

        $activities = $user->providedActivities()
            ->with('destination')
            ->latest()
            ->get();

        $bookings = $earnings->allBookingsQuery($user)
            ->with(['activity.destination', 'user'])
            ->latest()
            ->paginate(15);

        $totalEarned = $earnings->totalEarned($user);
        $countQualifying = $earnings->countQualifying($user);
        $commissionPercent = $earnings->commissionPercent();
        $dailySeries = $earnings->dailyEarningsSeries($user, 7);
        $monthlySeries = $earnings->monthlyEarningsSeries($user, 6);

        $latestApplication = $user->latestContentProviderApplication;

        return view('admin.content-providers.provider-show', compact(
            'user',
            'activities',
            'bookings',
            'totalEarned',
            'countQualifying',
            'commissionPercent',
            'dailySeries',
            'monthlySeries',
            'latestApplication'
        ));
    }

    public function updateProvider(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->is_content_provider, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'activity_type' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.providers.show', $user)
            ->with('success', 'تم تحديث بيانات المزوّد.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        abort_unless($user->is_content_provider, 404);

        if ($user->isApprovedContentProvider()) {
            $user->update([
                'content_provider_status' => 'rejected',
                'can_login' => false,
            ]);

            return back()->with('success', 'تم تعليق المزوّد (تعطيل الدخول).');
        }

        if (
            $user->content_provider_status !== 'pending'
            && ($user->content_provider_status === 'rejected' || ! $user->can_login)
        ) {
            $user->update([
                'content_provider_status' => 'approved',
                'can_login' => true,
            ]);

            return back()->with('success', 'تم إعادة تفعيل المزوّد.');
        }

        return back()->with('info', 'لا يمكن تغيير حالة المزوّد من هذه الشاشة أثناء انتظار اعتماد الطلب الأولي.');
    }
}

