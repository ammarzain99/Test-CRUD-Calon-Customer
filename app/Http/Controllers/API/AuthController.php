<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($req->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email / Password salah'
            ], 401);
        }

        $user = Auth::user();

        // Log activity login
        ActivityLogService::log('login', 'User', $user->id);

        return response()->json([
            'token' => $user->createToken('admin')->plainTextToken,
            'user' => $user
        ]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();

        ActivityLogService::log('logout', 'User', $req->user()->id);

        return response()->json(['message' => 'Logout success']);
    }
}
