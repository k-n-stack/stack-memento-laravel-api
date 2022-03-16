<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login (Request $request) {

        // return ['hello', 'hello'];

        if (!Auth::attempt($request->only('email', 'password'))) {
            return ['status' => 'unauthenticated'];
        }

        $user = User::where('email', $request['email'])->firstOfFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout () {
        auth()->user()->tokens()->delete();

        return ['status' => 'logout'];
    }
}
