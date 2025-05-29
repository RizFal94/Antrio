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
    //menapilkan semua cs
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
        $customerServices = CustomerService::with('user:id,name')
            ->select('id', 'name', 'prefix', 'status', 'user_id')
            ->get();

        $customerServices->transform(function ($cs) {
            $cs->user_name = $cs->user ? $cs->user->name : null;
            unset($cs->user);
            return $cs;
        });

        return response()->json($customerServices);
    }

    // Mengaktifkan customer service berdasarkan ID
    public function aktifkan($id)
    {
        $user = Auth::user();

        $customerService = CustomerService::findOrFail($id);

        if ($customerService->status) {
            return response()->json(['message' => 'Customer service sudah aktif.'], 400);
        }

        CustomerService::where('user_id', $user->id)
            ->where('status', true)
            ->update([
                'user_id' => null,
                'status' => false,
            ]);

        $customerService->update([
            'user_id' => $user->id,
            'status' => true,
        ]);

        return response()->json([
            'message' => 'Customer service berhasil diaktifkan.',
            'data' => $customerService->only(['id', 'name', 'prefix', 'status', 'user_id'])
        ]);
    }

    public function nonaktifkan($id)
    {
        $user = Auth::user();

        $customerService = CustomerService::findOrFail($id);

        if ($customerService->user_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk menonaktifkan CS ini.'], 403);
        }

        $customerService->update([
            'user_id' => null,
            'status' => false,
        ]);

        return response()->json([
            'message' => 'Customer service berhasil dinonaktifkan.',
            'data' => $customerService->only(['id', 'name', 'prefix', 'status', 'user_id']),
        ]);
    }

    public function ambilBerikutnya(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak terautentikasi.'], 401);
        }

        $customerService = CustomerService::where('user_id', $user->id)
                                        ->where('status', true)
                                        ->first();

        if (!$customerService) {
            return response()->json(['message' => 'aktifkan customer service terlebih dahulu.'], 403);
        }

        $prefix = $customerService->prefix;
        $today = Carbon::today();

        $berikutnya = Customer::with([
                'customerService.service', 
            ])
            ->whereDate('tanggal', $today)
            ->where('dilayani', false)
            ->where('skip', false)
            ->whereHas('customerService', function ($query) use ($prefix) {
                $query->where('prefix', $prefix);
            })
            ->orderBy('urutan')
            ->first();

        if (!$berikutnya) {
            return response()->json(['message' => 'Tidak ada antrean tersedia.'], 404);
        }

        $berikutnya->update(['dilayani' => true]);

        return response()->json([
            'message' => 'Antrian berikutnya ditemukan dan sedang dilayani',
            'data' => [
                'id' => $berikutnya->id,
                'urutan' => $berikutnya->urutan,
                'created_at' => $berikutnya->created_at,
                'customer_service' => [
                    'prefix' => $berikutnya->customerService->prefix,
                    'service' => $berikutnya->customerService->service->service ?? null
                ]
            ]
        ]);
    }

    public function skip($id)
    {
        $user = Auth::user();
        $customerService = CustomerService::where('user_id', $user->id)->where('status', true)->first();

        if (!$customerService) {
            return response()->json(['message' => 'aktifkan customer service terlebih dahulu.'], 403);
        }

        $antrian = Customer::with(['layanan', 'customerService'])->findOrFail($id);

        if ($antrian->customerService->prefix !== $customerService->prefix) {
            return response()->json(['message' => 'Antrian ini bukan milik CS Anda.'], 403);
        }

        $antrian->update([
            'dilayani' => false,
            'skip' => true,
        ]);

        return response()->json([
            'message' => 'Antrian berhasil diskip.',
            'data' => $antrian,
        ]);
    }

    public function selesai($id)
    {
        $user = Auth::user();
        $customerService = CustomerService::where('user_id', $user->id)->where('status', true)->first();

        if (!$customerService) {
            return response()->json(['message' => 'aktifkan customer service terlebih dahulu.'], 403);
        }

        $antrian = Customer::with(['layanan', 'customerService'])->findOrFail($id);

        if ($antrian->customerService->prefix !== $customerService->prefix) {
            return response()->json(['message' => 'Antrian ini bukan milik CS Anda.'], 403);
        }

        $antrian->update([
            'dilayani' => true,
            'skip' => true,
        ]);

        return response()->json([
            'message' => 'Layanan selesai.',
            'data' => $antrian,
        ]);
    }

}
