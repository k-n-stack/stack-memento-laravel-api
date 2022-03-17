<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login (Request $request) {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return [
                'status' => 'unauthenticated',
                'token' => '',
                'token_type' => '',
                'email' => '',
                'pseudonym' => '',
                'image_url' => '',
                'email_verified_at' => '',
                'ip' => $request->ip,
            ];
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'authenticated',
            'token' => $token,
            'token_type' => 'Bearer',
            'email' => $user->email,
            'pseudonym' => $user->pseudonym,
            'image_url' => $user->image_url,
            'email_verified_at' => $user->email_verified_at,
        ]);
    }

    public function logout () {
        auth()->user()->tokens()->delete();

        return ['status' => 'logout'];
    }
}
