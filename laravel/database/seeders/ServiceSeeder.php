<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'Setor/Tarik Tunai',
            'Transfer & Pembayaran',
            'Penukaran Uang',
            'Buka Rekening',
            'Kartu & E-Banking',
            'Informasi & Keluhan'
        ];

        foreach ($services as $service) {
            DB::table('services')->insert([
                'service' => $service,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
