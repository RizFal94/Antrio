<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Service;

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
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        $service = Service::create([
            'service' => $request->service,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil ditambahkan',
            'data' => $service
        ]);
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['status' => 'error', 'message' => 'Layanan tidak ditemukan'], 404);
        }

        $request->validate([
            'service' => 'required|string|max:255',
        ]);

        $service->update([
            'service' => $request->service,
        ]);

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

        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil dihapus'
        ]);
    }
}
