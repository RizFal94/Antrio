<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Service;
use App\Models\CustomerService;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function storeService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|max:255',
            'prefix' => 'required|string|max:5|alpha',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // maksimal 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        // Simpan Service
        $service = Service::create([
            'service' => $request->service,
            'prefix' => strtoupper($request->prefix),
            'image' => $imagePath,
        ]);

        // Tambahkan otomatis CustomerService
        $customerService = CustomerService::create([
            'service_id' => $service->id,
            'user_id' => null,
            'name' => 'CS ' . $service->service,
            'prefix' => $service->prefix,
            'status' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan dan Customer Service berhasil ditambahkan',
            'data' => [
                'service' => $service,
                'customer_service' => $customerService,
            ]
        ]);
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['status' => 'error', 'message' => 'Layanan tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'service' => 'required|string|max:255',
            'prefix' => 'required|string|max:5|alpha',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        // Ganti gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            // Upload gambar baru
            $imagePath = $request->file('image')->store('services', 'public');
            $service->image = $imagePath;
        }

        // Update data lainnya
        $service->service = $request->service;
        $service->prefix = strtoupper($request->prefix);
        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil diubah',
            'data' => $service
        ]);
    }

    public function deleteService($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['status' => 'error', 'message' => 'Layanan tidak ditemukan'], 404);
        }

        // Hapus gambar dari storage jika ada
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil dihapus'
        ]);
    }
}
