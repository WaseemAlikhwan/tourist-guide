<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['bookings', 'favorites'])
            ->latest()
            ->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['user', 'admin'])],
        ]);

        // منع إنشاء مستخدم بصلاحية admin
        if ($validated['role'] === 'admin') {
            return redirect()->route('admin.users.create')
                ->withInput()
                ->with('error', 'لا يمكن إنشاء مستخدم بصلاحية مدير من لوحة التحكم');
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        $user->load([
            'bookings' => function($query) {
                $query->with('activity')->latest()->limit(10);
            },
            'favorites' => function($query) {
                $query->with('favoritable')->latest()->limit(10);
            },
            'badges'
        ]);

        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'total_favorites' => $user->favorites()->count(),
            'total_badges' => $user->badges()->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['user', 'admin'])],
        ]);

        // منع تغيير role إلى admin
        if ($validated['role'] === 'admin' && $user->role !== 'admin') {
            return redirect()->route('admin.users.edit', $user)
                ->withInput()
                ->with('error', 'لا يمكن تغيير صلاحية المستخدم إلى مدير');
        }

        // منع تغيير role من admin إلى user
        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            return redirect()->route('admin.users.edit', $user)
                ->withInput()
                ->with('error', 'لا يمكن تغيير صلاحية المدير');
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        // منع حذف المستخدم الحالي
        if (auth()->guard('admin')->check() && auth()->guard('admin')->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        // منع حذف المستخدمين بصلاحية admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكن حذف المستخدمين بصلاحية مدير');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}

