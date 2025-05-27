<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                "email" => ["required", "email"],
                "password" => ["required"]
            ]);

            $user = User::where("email", strtolower($request->email))->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(["message" => "Email atau Password salah"], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "message" => "Login Success",
                "access_token" => $token,
                "token_type" => "Bearer",
                "user" => [
                    "name" => $user->name,
                    "email" => $user->email,
                    "role" => $user->role
                ]
            ], 200);
            
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 400);
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Ambil CS yang aktif dari user (jika ada)
        $activeCs = $user->customerService()->where('status', true)->first();

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'active_cs' => $activeCs ? [
                'id' => $activeCs->id,
                'name' => $activeCs->name,
                'prefix' => $activeCs->prefix
            ] : null
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            // Nonaktifkan CS jika ada yang aktif
            $cs = $user->customerService()->where('status', true)->first();

            if ($cs) {
                // Set status ke false dan hapus user_id
                $cs->update([
                    'status' => false,
                    'user_id' => null
                ]);
            }

            // Hapus token akses
            $user->currentAccessToken()->delete();

            return response()->json(["message" => "Berhasil Logout"], 200);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 400);
        }
    }
}
