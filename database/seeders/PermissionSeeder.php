<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'list-brand',
            'create-brand',
            'edit-brand',
            'delete-brand',
            'list-product',
            'create-product',
            'edit-product',
            'delete-product',
            'list-dealer',
            'create-dealer',
            'edit-dealer',
            'delete-dealer',
            'list-customer',
            'create-customer',
            'edit-customer',
            'delete-customer',
            'list-purchase',
            'create-purchase',
            'edit-purchase',
            'delete-purchase',
            'show-purchase',
            'list-sale',
            'create-sale',
            'edit-sale',
            'delete-sale',
            'show-sale',
            'list-finance',
            'create-finance',
            'edit-finance',
            'delete-finance',
            'show-finance',
            'list-finance-master',
            'create-finance-master',
            'edit-finance-master',
            'delete-finance-master',
            'show-finance-master',
            'list-user',
            'create-user',
            'edit-user',
            'delete-user',
            'list-role',
            'create-role',
            'edit-role',
            'delete-role',
            'list-permission',
            'create-permission',
            'edit-permission',
            'delete-permission',
            'list-deduction',
            'create-deduction',
            'edit-deduction',
            'delete-deduction',
        ];

        foreach($permissions as $per){
            Permission::create(['name' => $per]);
        }
    }
}
