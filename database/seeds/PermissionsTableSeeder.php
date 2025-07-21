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
                'title'      => 'akses_manajemen_pengguna',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '2',
                'title'      => 'buat_izin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '3',
                'title'      => 'edit_izin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '4',
                'title'      => 'lihat_izin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '5',
                'title'      => 'hapus_izin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '6',
                'title'      => 'akses_izin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '7',
                'title'      => 'buat_peran',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '8',
                'title'      => 'edit_peran',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '9',
                'title'      => 'lihat_peran',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '10',
                'title'      => 'hapus_peran',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '11',
                'title'      => 'akses_peran',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '12',
                'title'      => 'buat_pengguna',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '13',
                'title'      => 'edit_pengguna',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '14',
                'title'      => 'lihat_pengguna',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '15',
                'title'      => 'hapus_pengguna',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '16',
                'title'      => 'akses_pengguna',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '17',
                'title'      => 'buat_paket',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '18',
                'title'      => 'edit_paket',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '19',
                'title'      => 'lihat_paket',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '20',
                'title'      => 'hapus_paket',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '21',
                'title'      => 'akses_paket',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '22',
                'title'      => 'tambah_pelatih',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '23',
                'title'      => 'edit_pelatih',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '24',
                'title'      => 'lihat_pelatih',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '25',
                'title'      => 'hapus_pelatih',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '26',
                'title'      => 'akses_pelatih',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '27',
                'title'      => 'tambah_murid',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '28',
                'title'      => 'edit_murid',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '29',
                'title'      => 'lihat_murid',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '30',
                'title'      => 'hapus_murid',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '31',
                'title'      => 'akses_murid',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '32',
                'title'      => 'buat_jadwal',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '33',
                'title'      => 'edit_jadwal',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '34',
                'title'      => 'lihat_jadwal',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '35',
                'title'      => 'hapus_jadwal',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '36',
                'title'      => 'akses_jadwal',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Permission::insert($permissions);
    }
}
