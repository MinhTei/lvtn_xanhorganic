<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $staffRole = Role::where('name', 'staff')->first();
        $permissions = Permission::all();

        // Admin: toàn bộ quyền
        if ($adminRole) {
            $adminRole->permissions()->sync($permissions->pluck('id'));
        }

        // Staff: sản phẩm, đơn hàng, coupon, đánh giá
        if ($staffRole) {
            $staffPermissions = $permissions->whereIn('name', [
                'manage_products',
                'manage_orders',
                'manage_coupons',
                'manage_reviews',
            ]);
            $staffRole->permissions()->sync($staffPermissions->pluck('id'));
        }
    }
}
