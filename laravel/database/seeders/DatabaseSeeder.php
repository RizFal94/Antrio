<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed User
        User::create([
            'name' => 'Ahmad Rizqi Satriany',
            'email' => 'admin@antrio.com',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Lukmanul Karim',
            'email' => 'lukman@antrio.com',
            'password' => Hash::make('lukman'),
            'role' => 'cs'
        ]);
        User::create([
            'name' => 'Andi oioi',
            'email' => 'andi@antrio.com',
            'password' => Hash::make('andi'),
            'role' => 'cs'
        ]);

        User::create([
            'name' => 'Sakurajima Mai',
            'email' => 'mai@antrio.com',
            'password' => Hash::make('mai'),
            'role' => 'cs'
        ]);

        // Seed Services
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

        // Seed Customer Services
        $servicesFromDb = DB::table('services')->get();

        foreach ($servicesFromDb as $service) {
            DB::table('customer_services')->insert([
                'service_id' => $service->id,
                'user_id' => null,
                'name' => 'CS ' . $service->service,
                'prefix' => $service->prefix,
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
