<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ContentProviderApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private array $allowedSocialProviders = ['google', 'facebook'];

    public function showLoginForm()
    {
        return view('website.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // منع تسجيل الدخول إذا كان الحساب موقوفاً عن تسجيل الدخول
            if (!Auth::user()->can_login) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'لا يمكنك تسجيل الدخول حالياً. إذا كنت مزوّد محتوى فيجري حالياً مراجعة طلبك من قبل الإدارة.',
                ])->onlyInput('email');
            }

            // إذا كان المستخدم admin، يوجه إلى لوحة التحكم
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('website.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'تم إنشاء حسابك بنجاح!');
    }

    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, $this->allowedSocialProviders, true)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, $this->allowedSocialProviders, true)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Throwable $exception) {
            return redirect()
                ->route('login')
                ->with('error', 'تعذر تسجيل الدخول عبر الحساب الاجتماعي. حاول مرة أخرى.');
        }

        $email = $socialUser->getEmail();
        if (!$email) {
            return redirect()
                ->route('login')
                ->with('error', 'تعذر الحصول على البريد الإلكتروني من الحساب الاجتماعي.');
        }

        $user = User::where('auth_provider', $provider)
            ->where('auth_provider_id', $socialUser->getId())
            ->first();

        if (!$user) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            $user->fill([
                'name' => $user->name ?: ($socialUser->getName() ?: $socialUser->getNickname() ?: 'مستخدم'),
                'auth_provider' => $provider,
                'auth_provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'مستخدم',
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'auth_provider' => $provider,
                'auth_provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'role' => 'user',
                'can_login' => true,
            ]);
        }

        if (!$user->can_login) {
            return redirect()
                ->route('login')
                ->with('error', 'لا يمكنك تسجيل الدخول حالياً. حسابك بانتظار المراجعة.');
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended('/');
    }

    /**
     * نموذج تسجيل مزوّد محتوى سياحي (مع توثيق واعتماد).
     */
    public function showProviderRegisterForm()
    {
        return view('website.auth.provider-register');
    }

    /**
     * حفظ طلب تسجيل مزوّد المحتوى وإرساله للإدارة للمراجعة.
     */
    public function registerProvider(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()
                ->route('login')
                ->with('info', 'يرجى تسجيل الدخول أولاً ثم تقديم طلب مزوّد المحتوى.');
        }

        if ($user->isApprovedContentProvider()) {
            return redirect()
                ->route('provider.dashboard')
                ->with('info', 'حسابك معتمد بالفعل كمزوّد محتوى.');
        }

        $latestApplication = ContentProviderApplication::where('user_id', $user->id)
            ->latest('id')
            ->first();

        if ($latestApplication && $latestApplication->status === 'approved') {
            return redirect()
                ->route('provider.dashboard')
                ->with('info', 'تم اعتماد حسابك مسبقاً كمزوّد محتوى.');
        }

        if ($latestApplication && $latestApplication->status === 'pending') {
            return redirect()
                ->route('provider.application-status')
                ->with('info', 'لديك طلب مزوّد محتوى قيد المراجعة حالياً.');
        }

        if ($user->is_content_provider && $user->content_provider_status === 'pending') {
            return redirect()
                ->route('provider.application-status')
                ->with('info', 'لديك طلب مزوّد محتوى قيد المراجعة حالياً.');
        }

        $validated = $request->validate([
            'activity_type' => ['required', 'string', 'max:255'],
            'commercial_registration' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'tourism_license' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'ownership_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $user->update([
            'is_content_provider' => true,
            'content_provider_status' => 'pending',
            'activity_type' => $validated['activity_type'],
            'can_login' => true,
        ]);

        $commercialPath = null;
        $licensePath = null;
        $ownershipPath = null;

        if ($request->hasFile('commercial_registration')) {
            $commercialPath = $request->file('commercial_registration')
                ->store('provider-documents', 'public');
        }

        if ($request->hasFile('tourism_license')) {
            $licensePath = $request->file('tourism_license')
                ->store('provider-documents', 'public');
        }

        if ($request->hasFile('ownership_document')) {
            $ownershipPath = $request->file('ownership_document')
                ->store('provider-documents', 'public');
        }

        ContentProviderApplication::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'activity_type' => $validated['activity_type'],
            'commercial_registration_path' => $commercialPath,
            'tourism_license_path' => $licensePath,
            'ownership_document_path' => $ownershipPath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('provider.application-status')
            ->with('success', 'تم إرسال طلبك كمزوّد محتوى بنجاح، وسيتم مراجعته من قبل الإدارة.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

