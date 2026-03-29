<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions so you don't get cache errors when re-seeding
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create specific permissions
        Permission::create(['name' => 'manage university events']);
        Permission::create(['name' => 'manage college events']);
        Permission::create(['name' => 'manage organization events']);
        Permission::create(['name' => 'send sms announcements']);

        // 2. Create Roles and assign permissions
        
        $superAdmin = Role::create(['name' => 'Super Admin']);
        // Super Admins get everything
        $superAdmin->givePermissionTo(Permission::all());

        $collegeAdmin = Role::create(['name' => 'College Admin']);
        // College Admins (e.g., CITC Council) manage their own turf
        $collegeAdmin->givePermissionTo(['manage college events']);

        $orgOfficer = Role::create(['name' => 'Organization Officer']);
        // Officers manage their club and can blast text messages
        $orgOfficer->givePermissionTo(['manage organization events', 'send sms announcements']);

        $student = Role::create(['name' => 'Student']);
        // Regular students just view things, so they don't need elevated backend permissions assigned here
    }
}