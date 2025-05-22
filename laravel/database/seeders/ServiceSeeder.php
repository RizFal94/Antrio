<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['service' => 'Setor/Tarik Tunai', 'prefix' => 'ST'],
            ['service' => 'Transfer & Pembayaran', 'prefix' => 'TP'],
            ['service' => 'Penukaran Uang', 'prefix' => 'PU'],
            ['service' => 'Buka Rekening', 'prefix' => 'BR'],
            ['service' => 'Kartu & E-Banking', 'prefix' => 'KE'],
            ['service' => 'Informasi & Keluhan', 'prefix' => 'IK'],
        ];

        foreach ($services as $item) {
            DB::table('services')->insert([
                'service' => $item['service'],
                'prefix' => $item['prefix'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
