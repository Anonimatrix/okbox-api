<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => "required|email",
            "password" => 'required'
        ]);

        if (!Auth::attempt($request->all())) {
            return response()->json(['error' => 'invalid credentials']);
        }

        $token = $request->user()->createToken($request->user()->email)->plainTextToken;

        return response()->json([
            'token' =>  $token
        ]);
    }
}
