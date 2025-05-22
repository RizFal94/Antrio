<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
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
                "message" => "Registrasi Berhasil",
                "access_token" => $token,
                "token_type" => "Bearer"
            ], 201);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 400);
        }
    }

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

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(["message" => "Berhasil Logout"], 200);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 400);
        }
    }
}
