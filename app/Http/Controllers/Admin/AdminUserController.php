<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|regex:/^[0-9]{10,12}$/',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:pending,active,blocked,deleted',
        ], [
            'email.unique' => 'Email đã tồn tại.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
        ]);

        if ($msg = $this->guardRoleAssign(null, (int) $data['role_id'])) {
            return back()->withInput()->with('error', $msg);
        }

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Thêm người dùng thành công.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Không cho tự hạ/khóa chính mình
        if ($user->id === auth()->id() && $request->status !== 'active') {
            return back()->with('error', 'Bạn không thể tự khóa/xóa chính mình.');
        }

        // Staff không được sửa tài khoản admin
        if ($user->isAdmin() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Bạn không có quyền chỉnh sửa tài khoản admin.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|regex:/^[0-9]{10,12}$/',
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:pending,active,blocked,deleted',
        ], [
            'email.unique' => 'Email đã tồn tại.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if ($msg = $this->guardRoleAssign($user, (int) $data['role_id'])) {
            return back()->withInput()->with('error', $msg);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật người dùng thành công.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể xóa chính mình.');
        }

        if ($user->isAdmin() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Bạn không có quyền vô hiệu hóa tài khoản admin.');
        }

        // Soft-ish: đánh dấu deleted thay vì xóa cứng (giữ đơn hàng)
        $user->update(['status' => 'deleted']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã vô hiệu hóa người dùng.');
    }

    /** Chỉ admin được gán role admin; không tự đổi role của chính mình sang admin nếu đang là staff. */
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
