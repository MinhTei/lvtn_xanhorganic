<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage_users',
            'manage_products',
            'manage_categories',
            'manage_orders',
            'manage_contacts',
            'manage_coupons',
            'manage_reviews', 
            'manage_recipes',
            'add_recipes',
            'edit_recipes',
            'delete_recipes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
