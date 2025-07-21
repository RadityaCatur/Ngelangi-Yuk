<?php

use App\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'username'       => 'admin',
                'password'       => '$2y$10$Y.jEitizf.DW3V7gxCnMr.SdWN2i1w4gobo28vTLGaFajqcjUl8Oy',
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];

        User::insert($users);
    }
}
