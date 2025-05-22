<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerServiceRequest;
use App\Http\Requests\UpdateCustomerServiceRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerServiceController extends Controller
{
    //1. menapilkan semua cs
    public function index()
    {
        return response()->json(CustomerService::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerServiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerService $customerService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerServiceRequest $request, CustomerService $customerService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerService $customerService)
    {
        //
    }

    public function showCustomerServices()
    {
        $customerServices = CustomerService::select('id', 'name', 'prefix', 'status')->get();

        return response()->json($customerServices);
    } 

    // Mengaktifkan customer service berdasarkan ID
    public function aktifkan($id)
    {
        $user = Auth::user();

        // Cek apakah CS yang dimaksud ada
        $customerService = CustomerService::findOrFail($id);

        // Cek apakah CS ini sudah aktif
        if ($customerService->status) {
            return response()->json(['message' => 'Customer service sudah aktif.'], 400);
        }

        // Nonaktifkan semua CS lain yang sebelumnya aktif untuk user ini
        CustomerService::where('user_id', $user->id)
            ->where('status', true)
            ->update([
                'user_id' => null,
                'status' => false,
            ]);

        // Aktifkan CS yang dimaksud untuk user saat ini
        $customerService->update([
            'user_id' => $user->id,
            'status' => true,
        ]);

        return response()->json([
            'message' => 'Customer service berhasil diaktifkan.',
            'data' => $customerService->only(['id', 'name', 'prefix', 'status', 'user_id'])
        ]);
    }

    // Menonaktifkan customer service berdasarkan ID
    public function nonaktifkan($id)
{
    $user = Auth::user();

    $customerService = CustomerService::findOrFail($id);

    // Pastikan CS yang dimaksud dimiliki oleh user yang sedang login
    if ($customerService->user_id !== $user->id) {
        return response()->json(['message' => 'Anda tidak memiliki akses untuk menonaktifkan CS ini.'], 403);
    }

    // Nonaktifkan CS
    $customerService->update([
        'user_id' => null,
        'status' => false,
    ]);

    return response()->json([
        'message' => 'Customer service berhasil dinonaktifkan.',
        'data' => $customerService->only(['id', 'name', 'prefix', 'status', 'user_id']),
    ]);
}

    // Ambil antrian berikutnya (yang belum terlayani dan tidak diskip)
    public function ambilBerikutnya()
    {
        $today = Carbon::today();

        $berikutnya = Customer::whereDate('tanggal', $today)
            ->where('terlayani', false)
            ->where('skip', false)
            ->orderBy('urutan')
            ->first();

        if (!$berikutnya) {
            return response()->json(['message' => 'Tidak ada antrian tersedia']);
        }

        return response()->json([
            'message' => 'Antrian berikutnya ditemukan',
            'data' => $berikutnya,
        ]);
    }


    //Customer Service menekan tombol Skip
    public function skip($id)
    {
        $antrian = Customer::findOrFail($id);
        $antrian->update([
            'terlayani' => true,
            'skip' => true,
        ]);

        return response()->json(['message' => 'Antrian diskip']);
    }


    //Customer Service menekan tombol 'Selesai'
    public function selesai($id)
    {
        $antrian = Customer::findOrFail($id);
        $antrian->update([
            'terlayani' => true,
        ]);

        return response()->json(['message' => 'Layanan selesai']);
    }

}
