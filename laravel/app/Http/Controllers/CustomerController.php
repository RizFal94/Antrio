<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    //Buat antrian baru (untuk pengguna umum tanpa login)
    public function ambilAntrian(Request $request)
    {
        $today = Carbon::today();
        $waktuSekarang = Carbon::now();
        $serviceId = $request->input('service_id');

        if (!$serviceId) {
            return response()->json(['message' => 'service_id dibutuhkan'], 400);
        }

        $prefixMap = [
            1 => 'ST', // Setor/Tarik Tunai
            2 => 'TP', // Transfer & Pembayaran
            3 => 'PU', // Penukaran Uang
            4 => 'BR', // Buka Rekening
            5 => 'KE', // Kartu & E-Banking
            6 => 'IK', // Informasi & Keluhan
        ];

        $prefix = $prefixMap[$serviceId] ?? 'XX';

        // Cari CS yang aktif untuk layanan ini
        $cs = \App\Models\CustomerService::where('service_id', $serviceId)
            ->where('status', 'aktif')
            ->first();

        if (!$cs) {
            return response()->json([
                'message' => 'Tidak ada customer service yang aktif untuk layanan ini',
            ], 400);
        }

        $urutanTerakhir = Customer::whereDate('tanggal', $today)->max('urutan') ?? 0;
        $urutanBaru = $urutanTerakhir + 1;

        $antrianBaru = Customer::create([
            'tanggal' => $waktuSekarang,
            'urutan' => $urutanBaru,
            'terlayani' => false,
            'skip' => false,
            'customer_service_id' => $cs->id,
        ]);

        $kodeAntrian = $prefix . str_pad($urutanBaru, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'message' => 'Berhasil ambil antrian',
            'tanggal' => $waktuSekarang->format('Y-m-d'),
            'jam' => $waktuSekarang->format('H:i:s'),
            'kode_antrian' => $kodeAntrian,
            'data' => $antrianBaru,
        ]);
    }

    public function showMenunggu()
    {
        $today = Carbon::today();

        $antrian = Customer::whereDate('tanggal', $today)
            ->where('terlayani', false)
            ->where('skip', false)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian belum terlayani',
            'data' => $antrian,
        ]);
    }

    public function showSelesai()
    {
        $today = Carbon::today();

        $antrian = Customer::whereDate('tanggal', $today)
            ->where('terlayani', true)
            ->where('skip', false)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian yang sudah terlayani',
            'data' => $antrian,
        ]);
    }

    public function showSkip()
    {
        $today = Carbon::today();

        $antrian = Customer::whereDate('tanggal', $today)
            ->where('terlayani', true)
            ->where('skip', true)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian yang sudah terlayani',
            'data' => $antrian,
        ]);
    }
}
