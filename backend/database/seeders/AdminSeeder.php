<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermissions;
use App\Models\Staff;
use App\Models\StaffRoles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Chạy PermissionSeeder để đảm bảo tất cả quyền đã được tạo
        $this->call(PermissionSeeder::class);

        // 2. Tạo role Admin
        $role = Role::firstOrCreate(
            ['name' => 'Administrator'],
            ['description' => 'Quản trị viên hệ thống có toàn quyền']
        );

        // 3. Gắn tất cả quyền cho role Admin
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            RolePermissions::firstOrCreate([
                'role_id'       => $role->id,
                'permission_id' => $permission->id,
            ]);
        }

        // 4. Tạo tài khoản Admin
        $staff = Staff::firstOrCreate(
            ['email' => 'admin@bfd.com'],
            [
                'full_name' => 'Super Admin',
                'password'  => Hash::make('password123'),
                'is_active' => 1,
            ]
        );

        // 5. Gắn role Admin cho tài khoản Admin
        StaffRoles::firstOrCreate([
            'staff_id' => $staff->id,
            'role_id'  => $role->id,
        ]);

        $this->command->info('Đã tạo tài khoản admin@bfd.com / password123 với full quyền hạn thành công!');
    }
}
