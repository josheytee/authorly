<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // return response()->json(['message' => 'User endpoint is working!']);
        return response()->json($request->user());
    }
}
