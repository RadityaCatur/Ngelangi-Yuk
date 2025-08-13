<?php

use App\Service;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $services = [
            [
                'id'             => 1,
                'name'           => 'Kelas Anak Anak (Private 1 Murid 1 Pelatih)',
                'price'          => 70000,
                'kuota'          => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 2,
                'name'           => 'Kelas Anak Anak (Private 1 Murid 1 Pelatih)',
                'price'          => 255000,
                'kuota'          => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 3,
                'name'           => 'Kelas Anak Anak (Private 1 Murid 1 Pelatih)',
                'price'          => 490000,
                'kuota'          => 8,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 4,
                'name'           => 'Kelas Anak Anak (Private 2 Murid 1 Pelatih)',
                'price'          => 120000,
                'kuota'          => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 5,
                'name'           => 'Kelas Anak Anak (Private 2 Murid 1 Pelatih)',
                'price'          => 450000,
                'kuota'          => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 6,
                'name'           => 'Kelas Anak Anak (Private 3 Murid 1 Pelatih)',
                'price'          => 160000,
                'kuota'          => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 7,
                'name'           => 'Kelas Anak Anak (Private 3 Murid 1 Pelatih)',
                'price'          => 600000,
                'kuota'          => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 8,
                'name'           => 'Kelas Dewasa (Private 1 Murid 1 Pelatih)',
                'price'          => 75000,
                'kuota'          => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 9,
                'name'           => 'Kelas Dewasa (Private 1 Murid 1 Pelatih)',
                'price'          => 280000,
                'kuota'          => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 10,
                'name'           => 'Kelas Dewasa (Private 1 Murid 1 Pelatih)',
                'price'          => 540000,
                'kuota'          => 8,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 11,
                'name'           => 'Kelas Dewasa (Private 2 Murid 1 Pelatih)',
                'price'          => 130000,
                'kuota'          => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 12,
                'name'           => 'Kelas Dewasa (Private 2 Murid 1 Pelatih)',
                'price'          => 500000,
                'kuota'          => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 13,
                'name'           => 'Kelas Terapi / Home Visit',
                'price'          => 400000,
                'kuota'          => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 14,
                'name'           => 'Kelas Terapi / Home Visit',
                'price'          => 750000,
                'kuota'          => 8,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];

        Service::insert(values: $services);
    }
}
