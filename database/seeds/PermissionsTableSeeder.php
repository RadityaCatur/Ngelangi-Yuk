<?php

use App\Permission;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $permissions = [
            [
                'id'         => '1',
                'title'      => 'user_management_access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '2',
                'title'      => 'permission_create',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '3',
                'title'      => 'permission_edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '4',
                'title'      => 'permission_show',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '5',
                'title'      => 'permission_delete',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '6',
                'title'      => 'permission_access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '7',
                'title'      => 'role_create',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '8',
                'title'      => 'role_edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '9',
                'title'      => 'role_show',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '10',
                'title'      => 'role_delete',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '11',
                'title'      => 'role_access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '12',
                'title'      => 'user_create',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '13',
                'title'      => 'user_edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '14',
                'title'      => 'user_show',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '15',
                'title'      => 'user_delete',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '16',
                'title'      => 'user_access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '17',
                'title'      => 'service_create',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '18',
                'title'      => 'service_edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '19',
                'title'      => 'service_show',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '20',
                'title'      => 'service_delete',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '21',
                'title'      => 'service_access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '22',
                'title'      => 'employee_create',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '23',
                'title'      => 'employee_edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '24',
                'title'      => 'employee_show',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '25',
                'title'      => 'employee_delete',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '26',
                'title'      => 'employee_access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '27',
                'title'      => 'client_create',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '28',
                'title'      => 'client_edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '29',
                'title'      => 'client_show',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '30',
                'title'      => 'client_delete',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '31',
                'title'      => 'client_access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '32',
                'title'      => 'appointment_create',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '33',
                'title'      => 'appointment_edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '34',
                'title'      => 'appointment_show',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '35',
                'title'      => 'appointment_delete',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '36',
                'title'      => 'appointment_access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '37',
                'title'      => 'topup',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Permission::insert($permissions);
    }
}