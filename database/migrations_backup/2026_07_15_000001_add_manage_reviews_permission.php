<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

/**
 * Thêm permission manage_reviews cho DB đã seed trước đó.
 */
return new class extends Migration
{
    public function up(): void
    {
        $permission = Permission::firstOrCreate(['name' => 'manage_reviews']);

        $admin = Role::where('name', 'admin')->first();
        if ($admin && !$admin->permissions()->where('permissions.id', $permission->id)->exists()) {
            $admin->permissions()->attach($permission->id);
        }

        $staff = Role::where('name', 'staff')->first();
        if ($staff && !$staff->permissions()->where('permissions.id', $permission->id)->exists()) {
            $staff->permissions()->attach($permission->id);
        }
    }

    public function down(): void
    {
        $permission = Permission::where('name', 'manage_reviews')->first();
        if ($permission) {
            $permission->roles()->detach();
            $permission->delete();
        }
    }
};
