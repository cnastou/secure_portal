<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Send email verification
        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json(['message' => 'User registered. Please check your email to verify your account.'], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expiry' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    // CURRENT USER
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    // LOGOUT
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logged out']);
    }

    // VERIFY EMAIL
    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);

        // Check if email is already verified
        if ($user->email_verified_at) {
            return view('verify-email-success');
        }

        // Verify the email
        $user->update(['email_verified_at' => now()]);

        return view('verify-email-success');
    }

    // RESEND VERIFICATION EMAIL
    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Check if email is already verified
        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email is already verified.'], 200);
        }

        // Resend verification email
        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json(['message' => 'Verification email sent. Please check your email.'], 200);
    }

    // CREATE ADMIN (for testing/setup purposes)
    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        return response()->json(['message' => 'Admin user created successfully', 'user' => $user], 201);
    }
}
