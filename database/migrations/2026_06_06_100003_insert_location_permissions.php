<?php

use Illuminate\Database\Migrations\Migration;
use App\Permission;
use App\Role;

class InsertLocationPermissions extends Migration
{
    public function up()
    {
        $permissions = [
            ['title' => 'location_access'],
            ['title' => 'location_create'],
            ['title' => 'location_edit'],
            ['title' => 'location_show'],
            ['title' => 'location_delete'],
        ];

        // Insert permission ke tabel permissions
        foreach ($permissions as $permData) {
            $permission = Permission::firstOrCreate($permData);
            
            // Assign permission ke role Admin
            $adminRole = Role::where('title', 'Admin')->first();
            if ($adminRole && !$adminRole->permissions->contains($permission->id)) {
                $adminRole->permissions()->attach($permission->id);
            }
        }
    }

    public function down()
    {
        $titles = [
            'location_access', 
            'location_create', 
            'location_edit', 
            'location_show', 
            'location_delete'
        ];
        
        // Hapus relasi dari role Admin
        $adminRole = Role::where('title', 'Admin')->first();
        if ($adminRole) {
            $permissions = Permission::whereIn('title', $titles)->get();
            $adminRole->permissions()->detach($permissions->pluck('id'));
        }
        
        // Hapus permissions
        Permission::whereIn('title', $titles)->delete();
    }
}
