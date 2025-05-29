<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

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

    //Mengambil response login
    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

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

            $cs = $user->customerService()->where('status', true)->first();
            if ($cs) {
                $cs->update([
                    'status' => false,
                    'user_id' => null
                ]);
            }

            $user->currentAccessToken()->delete();

            return response()->json(["message" => "Berhasil Logout"], 200);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 400);
        }
    }
}
