<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Truncate the tables
        Permission::truncate();
        Role::truncate();
        
        // Enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create permissions
        $permissions = [
            'create role',
            'edit role',
            'view role',
            'delete role',
        ];

        foreach ($permissions as $permission) {
            // Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        // Create roles and assign permissions
        $roleWriter = Role::firstOrCreate(['name' => 'creator', 'guard_name' => 'api']);
        $roleWriter->givePermissionTo('create role');
        
        $roleWriter = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'api']);
        $roleWriter->givePermissionTo('edit role');

        $roleWriter = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        $roleWriter->givePermissionTo('view role');

        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $roleAdmin->syncPermissions($permissions);
    }
}