<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Carbon;
use App\Models\CustomerService;

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

        // Cari CS yang aktif untuk layanan ini
        $cs = CustomerService::where('service_id', $serviceId)
            ->where('status', true)
            ->first();

        if (!$cs) {
            return response()->json([
                'message' => 'Tidak ada customer service yang aktif untuk layanan ini',
            ], 400);
        }

        // Ambil prefix dari relasi ke tabel service
        $prefix = $cs->service->prefix ?? 'XX';

        // Hitung urutan baru
        $urutanTerakhir = Customer::whereDate('tanggal', $today)->max('urutan') ?? 0;
        $urutanBaru = $urutanTerakhir + 1;

        // Buat antrian baru
        $antrianBaru = Customer::create([
            'tanggal' => $waktuSekarang,
            'urutan' => $urutanBaru,
            'dilayani' => false,
            'skip' => false,
            'customer_service_id' => $cs->id,
        ]);

        // Ambil data lengkap dengan relasi
        $antrianBaru->load(['customerService.service']);

        // Buat kode antrian
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
        $today = Carbon::today()->toDateString();

        $antrian = Customer::with(['customerService.service'])
            ->whereDate('tanggal', $today)
            ->where('dilayani', false)
            ->where('skip', false)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian belum dilayani',
            'tanggal' => $today,
            'data' => $antrian,
        ]);
    }

    public function showDilayani()
    {
        $today = Carbon::today()->toDateString();

        $antrian = Customer::with(['customerService.service'])
            ->whereDate('tanggal', $today)
            ->where('dilayani', true)
            ->where('skip', false)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian yang sudah terlayani',
            'tanggal' => $today,
            'data' => $antrian,
        ]);
    }

    public function showSelesai()
    {
        $today = Carbon::today()->toDateString();

        $antrian = Customer::with(['customerService.service'])
            ->whereDate('tanggal', $today)
            ->where('dilayani', true)
            ->where('skip', true)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian yang sudah terlayani',
            'tanggal' => $today,
            'data' => $antrian,
        ]);
    }

    public function showSkip()
    {
        $today = Carbon::today()->toDateString();

        $antrian = Customer::with(['customerService.service'])
            ->whereDate('tanggal', $today)
            ->where('dilayani', false)
            ->where('skip', true)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian yang sudah di-skip',
            'tanggal' => $today,
            'data' => $antrian,
        ]);
    }

}
