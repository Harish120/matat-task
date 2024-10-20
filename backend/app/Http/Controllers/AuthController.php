<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('User System Access Token')->accessToken;

            return response()->json(['user' => $user, 'access_token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create token using Laravel Passport
        $token = $user->createToken('User System Access Token')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token
        ], 201);
    }

    public function user(Request $request)
    {
        return response()->json(Auth::user(), 200);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->token()->revoke();
            return response()->json(['message' => 'Successfully logged out'], 200);
        }

        return response()->json(['error' => 'User not authenticated'], 401);
    }
}
