<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Lấy role (đã được tạo ở PermissionSeeder)
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();

        // 2. Tạo tài khoản Admin (Full quyền qua role admin)
        $admin = Staff::updateOrCreate(
            ['email' => 'admin@ecom.com'],
            [
                'full_name'    => 'Super Administrator',
                'password'     => Hash::make('password123'),
                'phone_number' => '0987654321',
                'is_active'    => true,
            ]
        );
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // 3. Tạo tài khoản Manager (Quyền quản lý danh mục, SP qua role manager)
        $manager = Staff::updateOrCreate(
            ['email' => 'manager@ecom.com'],
            [
                'full_name'    => 'Store Manager',
                'password'     => Hash::make('password123'),
                'phone_number' => '0912345678',
                'is_active'    => true,
            ]
        );
        if ($managerRole) {
            $manager->roles()->syncWithoutDetaching([$managerRole->id]);
        }

        // 4. Tạo tài khoản Staff (Gán quyền trực tiếp Direct Permissions, ko qua Role)
        $staff = Staff::updateOrCreate(
            ['email' => 'staff@ecom.com'],
            [
                'full_name'    => 'Normal Staff',
                'password'     => Hash::make('password123'),
                'phone_number' => '0922334455',
                'is_active'    => true,
            ]
        );
        
        // Ví dụ: Chỉ cho staff này quyền Đọc đơn hàng và Đọc sản phẩm
        $viewOrders = Permission::where('module', 'orders')->where('action', 'view')->first();
        $viewProducts = Permission::where('module', 'products')->where('action', 'view')->first();
        
        $directPerms = array_filter([$viewOrders?->id, $viewProducts?->id]);
        
        if (!empty($directPerms)) {
            // Gán trực tiếp qua bảng trung gian staff_permissions (quan hệ permissions() trong Staff model)
            $staff->permissions()->syncWithoutDetaching($directPerms);
        }
    }
}
