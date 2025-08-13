<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::where('id', '!=', 37)->get();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

        // Role ID 2 & 3: hanya dapat permission id 33 dan 34
        $specific_permission_id = 34;

        Role::findOrFail(2)->permissions()->sync($specific_permission_id);
        Role::findOrFail(3)->permissions()->sync($specific_permission_id);

        Role::findOrFail(3)->permissions()->syncWithoutDetaching(33);
        Role::findOrFail(3)->permissions()->syncWithoutDetaching(37);
    }
}
