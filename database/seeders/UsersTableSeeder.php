<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'AdminUser',
            'email'=>'admin@gmail.com',
            'phone'=>'0966330634',
            'password'=>bcrypt('123456'),
            'status'=>'active',
            'role_id'=>1,
            'created_at'=>now(),
            'updated_at'=>now(),

        ]);
           User::create([
            'name' => 'Staff',
            'email'=>'staff@gmail.com',
            'phone'=>'0966330634',
            'password'=>bcrypt('123456'),
            'status'=>'active',
            'role_id'=>2,
            'created_at'=>now(),
            'updated_at'=>now(),

        ]);
           User::create([
            'name' => 'MinhTai',
            'email'=>'tai@gmail.com',
            'phone'=>'0966330634',
            'password'=>bcrypt('123456'),
            'status'=>'active',
            'role_id'=>3,
            'created_at'=>now(),
            'updated_at'=>now(),

        ]);
    }
}
