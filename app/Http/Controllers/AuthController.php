<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\Service;

class AuthController extends Controller
{
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:128', 'unique:users,email'],
            'pseudonym' => ['required', 'string', 'max:64', 'unique:users,pseudonym'],
            // At least 1 lowercase letter, 1 uppercase letter, 1 digit, 1 special char, no space, between 12 and 128 chars.
            'password' => ['required', 'string', 'min:12', 'max:128', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{12,128}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'email' => $request->email,
            'pseudonym' => $request->pseudonym,
            'password' => Hash::make($request->password)
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 'Registration success']);
    }

    public function verifyEmail (Request $request) {
        if (!$request->hasValidSignature()) {
            return response()->json(['status' => 'Invalid/Expired url provided']);
        }

        $user = User::findOrFail($request->id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    
        return response()->json(["status" => "Verification success"]);
    }

    public function login (Request $request) {
        $errorResponse = [
            'status' => 'unauthenticated',
            'token' => '',
            'token_type' => '',
            'email' => '',
            'pseudonym' => '',
            'image_url' => '',
            'email_verified_at' => '',
        ];

        $adminEmails = [
            'global@stackmemento.com',
        ];
                                                                /* vvv AVOID ADMIN EMAIL TO CONNECT vvv */
        if (!Auth::attempt($request->only('email', 'password')) /*|| in_array($request->email, $adminEmails)*/) {
            return $errorResponse;
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if (!$user->hasVerifiedEmail()) {
            $executed = RateLimiter::attempt(
                `resend_verification_email_{$user->id}`,
                $perMinute = 5,
                function () use ($user) {
                    $user->sendEmailVerificationNotification();
                }
            );
            if (!$executed) {
                return response()->json(['status' => 'Too many verification mail required']);
            }
            return response()->json(['status' => 'Verification required']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'authenticated',
            'token' => explode("|", $token)[1],
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

    // public function mail () {
    //     $data['title'] = "This is Test Mail Tuts Make";

    //     Mail::to('khin.nicolas@gmail.com')
    //         ->send(new Service());

    //     return ['end of mail controller method'];
    // }
}
