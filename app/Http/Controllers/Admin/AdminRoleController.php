<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class AdminRoleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:add_roles', only: ['create', 'store']),
            new Middleware('permission:edit_roles', only: ['edit', 'update']),
            new Middleware('permission:delete_roles', only: ['destroy']),
        ];
    }    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->with('permissions')
            ->orderBy('id')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('roles', 'name'),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ], [
            'name.required' => 'Vui lòng nhập tên role.',
            'name.unique' => 'Tên role đã tồn tại.',
            'name.alpha_dash' => 'Tên role chỉ gồm chữ, số, gạch ngang/dưới.',
        ]);

        $role = Role::create(['name' => strtolower($data['name'])]);
        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã thêm role "' . $role->name . '".');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $assigned = $role->permissions->pluck('id')->all();

        return view('admin.roles.edit', compact('role', 'permissions', 'assigned'));
    }

    public function update(Request $request, Role $role)
    {
        $permissionIds = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ])['permissions'] ?? [];

        if ($role->name === 'admin' && empty($permissionIds)) {
            return back()->with('error', 'Role admin nên có ít nhất một quyền.');
        }

        $role->permissions()->sync($permissionIds);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã cập nhật quyền cho role "' . $role->name . '".');
    }

    public function destroy(Role $role)
    {
        // hệ thống không được xóa
        if (in_array($role->name, ['admin', 'staff', 'customer'], true)) {
            return back()->with('error', 'Không thể xóa role hệ thống (admin / staff / customer).');
        }

        // ko xóa role của chính mình đang đăng nhập
        if (Auth::user()->role_id === $role->id) {
            return back()->with('error', 'Bạn không thể xóa role đang dùng của chính mình.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'Role vẫn còn người dùng. Hãy đổi/xóa user thuộc role này trước.');
        }

        $name = $role->name;
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã xóa role "' . $name . '".');
    }
}
