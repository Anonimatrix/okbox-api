<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageMail;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            "email" => 'email|required|unique:users',
            "password" => "required|min:8"
        ]);

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        event(new Registered($user));

        return response()->json(['status' => 'created'], 201);
    }
}
