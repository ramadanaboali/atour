<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permissions::getPermissions();

        foreach ($permissions as $key => $permission) {
            Permission::firstOrCreate(
                ['name' => $key, 'guard_name' => 'web'],
                ['name' => $key, 'guard_name' => 'web'],
            );
        }

        // create admin role
        Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web', 'display_name' => 'Admin'],
            ['name' => 'admin', 'guard_name' => 'web', 'display_name' => 'Admin'],
        );
        Role::Create(
            ['name' => 'company', 'display_name' => 'company', 'guard_name' => 'web']
        );

        $role = Role::where('name', 'admin')->first();
        if ($role) {
            $role->givePermissionTo(Permission::all());
        }

        $user = User::where('email', 'admin@admin.com')->first();
        if ($user) {
            $user->assignRole($role);
        }
    }
}
