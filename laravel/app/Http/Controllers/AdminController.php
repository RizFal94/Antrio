<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\User;
use App\Models\Service;
use App\Models\Customer;
use App\Models\CustomerService;

class AdminController extends Controller
{
    //Menampilkan semua user
    public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function addUser(Request $request)
    {
        try {
            $request["email"] = strtolower($request["email"]);

            $data = $request->validate([
                "name" => ["required"],
                "email" => ["required", "email", "unique:users"],
                "password" => ["required", "confirmed"]
            ]);

            $data['password'] = Hash::make($data['password']);
            $data['role'] = 'cs';

            $user = User::create($data);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "message" => "Berhasil Tambah User",
                "access_token" => $token,
                "token_type" => "Bearer"
            ], 201);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 400);
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        if ($user->role === 'admin') {
            return response()->json(['message' => 'User admin tidak dapat dihapus'], 403);
        }
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus']);
    }

    //Menampilkan semua riwayat antrian (ADMIN)
    public function showMenunggu()
    {
        $antrian = Customer::with(['customerService.service'])
            ->where('dilayani', false)
            ->where('skip', false)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian belum dilayani',
            'data' => $antrian,
        ]);
    }

    public function showDilayani()
    {
        $antrian = Customer::with(['customerService.service'])
            ->where('dilayani', true)
            ->where('skip', false)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian yang sudah terlayani',
            'data' => $antrian,
        ]);
    }

    public function showSelesai()
    {
        $antrian = Customer::with(['customerService.service'])
            ->where('dilayani', true)
            ->where('skip', true)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian yang sudah terlayani',
            'data' => $antrian,
        ]);
    }
    
    public function showSkip()
    {
        $antrian = Customer::with(['customerService.service'])
            ->where('dilayani', false)
            ->where('skip', true)
            ->orderBy('urutan')
            ->get();

        return response()->json([
            'message' => 'Daftar antrian yang sudah di-skip',
            'data' => $antrian,
        ]);
    }

    //controller Service relasi dengan Customer Service
    public function storeService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|max:255',
            'prefix' => 'required|string|max:5|alpha',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        $service = Service::create([
            'service' => $request->service,
            'prefix' => strtoupper($request->prefix),
            'image' => $imagePath,
        ]);

        $customerService = CustomerService::create([
            'service_id' => $service->id,
            'user_id' => null,
            'name' => $service->service,
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

        if ($request->hasFile('image')) {
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $imagePath = $request->file('image')->store('services', 'public');
            $service->image = $imagePath;
        }

        $service->service = $request->service;
        $service->prefix = strtoupper($request->prefix);
        $service->save();

        CustomerService::where('service_id', $service->id)->update([
            'name' => $service->service,
            'prefix' => $service->prefix
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan dan Customer Service berhasil diubah',
            'data' => $service
        ]);
    }

    public function deleteService($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['status' => 'error', 'message' => 'Layanan tidak ditemukan'], 404);
        }

        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        CustomerService::where('service_id', $service->id)->delete();
        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan dan Customer Service berhasil dihapus'
        ]);
    }
}
