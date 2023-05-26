<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="email@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="securePassword"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
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
