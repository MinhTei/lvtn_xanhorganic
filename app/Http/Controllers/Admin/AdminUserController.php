<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->latest();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load(['role', 'addresses']);
        $orderCount = $user->orders()->count();
        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('admin.users.show', compact('user', 'orderCount', 'recentOrders'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    // đổi role
    public function update(Request $request, User $user)
    {
        if ($user->isAdmin() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Bạn không có quyền chỉnh sửa tài khoản admin.');
        }

        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($msg = $this->guardRoleAssign($user, (int) $data['role_id'])) {
            return back()->withInput()->with('error', $msg);
        }

        $user->update(['role_id' => $data['role_id']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã cập nhật role cho người dùng.');
    }

    //khóa 
    public function toggleBlock(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể khóa chính mình.');
        }

        if ($user->isAdmin() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Bạn không có quyền khóa tài khoản admin.');
        }

        if ($user->status === 'pending') {
            return back()->with('error', 'Tài khoản chưa kích hoạt — không thể khóa/mở khóa.');
        }

        if ($user->status === 'active') {
            $user->update(['status' => 'blocked']);
            $msg = 'Đã khóa tài khoản.';
        } elseif ($user->status === 'blocked') {
            $user->update(['status' => 'active']);
            $msg = 'Đã mở khóa tài khoản.';
        } else {
            return back()->with('error', 'Không thể thay đổi trạng thái tài khoản này.');
        }

        return back()->with('success', $msg);
    }

    private function guardRoleAssign(?User $target, int $roleId): ?string
    {
        $adminRoleId = (int) Role::where('name', 'admin')->value('id');
        $actor = auth()->user();

        if ($roleId === $adminRoleId && !$actor->isAdmin()) {
            return 'Chỉ admin mới được gán quyền Admin.';
        }

        if ($target && $target->id === $actor->id && (int) $target->role_id !== $roleId && !$actor->isAdmin()) {
            return 'Bạn không thể tự đổi role của chính mình.';
        }

        return null;
    }
}
