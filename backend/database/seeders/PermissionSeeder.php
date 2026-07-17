<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'categories',
            'products',
            'attributes',
            'orders',
            'returns',
            'suppliers',
            'goods_receipts',
            'customers',
            'reviews',
            'coupons',
            'banners',
            'blogs',
            'staffs',
            'roles',
        ];

        $actions = ['read', 'create', 'update', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'module' => $module,
                    'action' => $action,
                ]);
            }
        }
    }
}
