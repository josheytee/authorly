<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HandlesResponse;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Regenerate the session to avoid fixation attacks
            // $request->session()->regenerate();

            // Set the HTTPOnly cookie with the token
            // $token = Auth::user()->createToken('auth_token')->plainTextToken;
            // Cookie::queue('token', $token, 60 * 24, null, null, true, true, false, 'Strict');

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                // Generate the token and set cookie
                response()->json(['message' => 'Logged in successfully'])
                    ->cookie('token', $user->createToken('your-app-name')->plainTextToken, 60, null, null, false, true);
            }
            return $this->respondWithSuccess($request);
            // Use the trait to respond to both API and Inertia requests
            return $this->respondWithRedirect($request, 'dashboard', $token);
        }

        // If login fails, use the trait to respond with error
        return $this->respondWithError($request, [
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function login2(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
            ])->cookie('token', $token, 60, null, null, true, true); // HTTPOnly cookie
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
