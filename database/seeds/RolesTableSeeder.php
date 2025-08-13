<?php

use App\Role;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $roles = [
            [
                'id'         => 1,
                'title'      => 'Admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 2,
                'title'      => 'Pelatih',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 3,
                'title'      => 'Murid',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Role::insert($roles);
    }
}
