<?php

namespace App\Http\Controllers\API;

use  App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
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
        // dd(Auth::attempt($credentials));
        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Regenerate the session to avoid fixation attacks
            // $request->session()->regenerate();

            // Set the HTTPOnly cookie with the token
            $token = Auth::user()->createToken('auth_token')->plainTextToken;

            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                // dd();
                // Generate the token and set cookie
                return response()->json(['message' => 'Logged in successfully'])
                    ->cookie('api_token', $token, 60 * 24, null, null, true, true); // 1 day expiration, HTTPOnly and Secure flags set

                return $this->respondWithSuccess($request, $user);
                // Use the trait to respond to both API and Inertia requests
                // return $this->respondWithRedirect($request, 'dashboard', $token);
            }
        }
        // If login fails, use the trait to respond with error
        return $this->respondWithError($request, [
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function register(RegisterRequest $request)
    {

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Optionally, you can create a token for the user after registration
        // $token = $user->createToken('YourAppName')->plainTextToken;

        // Return a response
        return response()->json([
            'message' => 'User registered successfully.',
            // 'token' => $token,
            'user' => $user,
        ], 201);
    }


    public function user(Request $request)
    {
        return $request->user();
    }
}
