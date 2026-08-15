<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define modules and their actions based on system requirements
        $modules = [
            'staff' => ['view', 'create', 'update', 'delete'],
            'roles' => ['view', 'create', 'update', 'delete'],
            'permissions' => ['view'],
            'customers' => ['view', 'create', 'update', 'delete'],
            'categories' => ['view', 'create', 'update', 'delete'],
            'products' => ['view', 'create', 'update', 'delete'],
            'orders' => ['view', 'update'],
            'coupons' => ['view', 'create', 'update', 'delete'],
            'reviews' => ['view', 'delete'],
            'blogs' => ['view', 'create', 'update', 'delete'],
            'banners' => ['view', 'create', 'update', 'delete'],
            'suppliers' => ['view', 'create', 'update', 'delete'],
            'goods_receipts' => ['view', 'create', 'update', 'delete'],
            'system_settings' => ['view', 'update'],
        ];

        $permissionIds = [];
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permission = Permission::updateOrCreate(
                    ['module' => $module, 'action' => $action]
                );
                $permissionIds[] = $permission->id;
            }
        }

        // Create Admin Role - name must be 'admin' to match Staff model check
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['description' => 'Super Administrator with all permissions']
        );

        // Sync all permissions to Admin
        $adminRole->permissions()->sync($permissionIds);

        // Create Manager Role (optional, for demo)
        $managerRole = Role::updateOrCreate(
            ['name' => 'manager'],
            ['description' => 'Manager role with catalog & order management permissions']
        );

        // Find permission IDs for products, categories, orders, and customers
        $managerPermissions = Permission::whereIn('module', ['products', 'categories', 'orders', 'customers'])
            ->pluck('id')
            ->toArray();
        $managerRole->permissions()->sync($managerPermissions);

        // Create a default administrator staff
        $adminStaff = Staff::updateOrCreate(
            ['email' => 'admin@ecomfashion.com'],
            [
                'full_name' => 'System Administrator',
                'password' => 'password123',
                'phone_number' => '0123456789',
                'is_active' => true,
            ]
        );

        // Attach Admin Role to Admin Staff if not already attached
        if (! $adminStaff->roles()->where('role_id', $adminRole->id)->exists()) {
            $adminStaff->roles()->attach($adminRole->id);
        }

        // Create a default manager staff
        $managerStaff = Staff::updateOrCreate(
            ['email' => 'manager@ecomfashion.com'],
            [
                'full_name' => 'Store Manager',
                'password' => 'password123',
                'phone_number' => '0987654321',
                'is_active' => true,
            ]
        );

        // Attach Manager Role to Manager Staff if not already attached
        if (! $managerStaff->roles()->where('role_id', $managerRole->id)->exists()) {
            $managerStaff->roles()->attach($managerRole->id);
        }
    }
}
