<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = DB::table('services')->get();

        $prefixMap = [
            'Setor/Tarik Tunai' => 'ST',
            'Transfer & Pembayaran' => 'TP',
            'Penukaran Uang' => 'PU',
            'Buka Rekening' => 'BR',
            'Kartu & E-Banking' => 'KE',
            'Informasi & Keluhan' => 'IK',
        ];

        foreach ($services as $service) {
            DB::table('customer_services')->insert([
                'service_id' => $service->id,
                'user_id' => null,
                'name' => 'CS ' . $service->service, 
                'prefix' => $prefixMap[$service->service] ?? 'XX',
                'status' => false, 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
