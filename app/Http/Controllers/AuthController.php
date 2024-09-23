<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            // Generate a token
            $token = bin2hex(random_bytes(40)); //:TODO  JWT can be considered

            // Set HTTPOnly cookie
            return response()->json(['message' => 'Login successful'])
                ->cookie('token', $token, 60 * 24, null, null, false, true); // HTTPOnly cookie
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}
