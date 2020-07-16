<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * Create the roles.
         *   - Super Admin
         *   - Building Manager
         *   - Help Desk
         *   - Viewer
         */
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $buildingManagerRole = Role::create(['name' => 'Building Manager']);
        $helpDeskRole = Role::create(['name' => 'Help Desk']);
        $viewerRole = Role::create(['name' => 'Viewer']);

        /**
         * Create the role permissions for building and assign to the given roles:
         *   1. Super Admin
         *     - View All Buildings
         *     - Create Building
         *     - View Building (Show the building detail)
         *     - update Building
         *     - delete Building
         *     - restore Building
         *     - Force delete Building (Permanently delete the building)
         *   2. Building Manager
         *     - View Building (Show the building detail)
         *     - Update Building
         *   3. Help Desk
         *     - View Building (Show the building detail)
         *   4. Viewer
         *     - View Building (Show the building detail)
         */

        // Create the building permissions
        $buildingPermissions = [
            'View All Buildings',
            'Create Building',
            'View Building',
            'Update Building',
            'Delete Building',
            'Restore Building',
            'Force Delete Building',
        ];

        // Create the building permissions for each roles
        $buildingRolePermissions = [
            'Building Manager' => [
                'View All Buildings',
                'View Building',
            ],
            'Help Desk'        => [
                'View All Buildings',
                'View Building',
            ],
            'Viewer'           => [
                'View All Buildings',
                'View Building',
            ],
        ];

        // Create the building permissions
        foreach ($buildingPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign the building permission to the given roles.
        foreach ($buildingRolePermissions as $name => $permissions) {
            $role = Role::where('name', $name)->first();
            $role->syncPermissions($permissions);
        }

        // Assign all permissions to super admin role
        $superAdminRole->syncPermissions(Permission::all());
    }
}
