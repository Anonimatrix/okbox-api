<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class ResetPasswordController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     summary="Send email to recover password",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="jhon@gmail.com"
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
    public function forgot(Request $request)
    {
        $request->validate(['email' => "required|email"]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(["status" => 'sended'], 200);
        }

        return response()->json(["error" => 'reset-link-doesnt-sendeed'], 400);
    }

    /**
     * @OA\Post(
     *     path="/reset-password/{token}",
     *     summary="Reset password with provided token and email",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *          name="token",
     *          in="path",
     *          required=true,
     *          description="Token to reset password",
     *          example="token"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="jhon@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="newSecurePassword"
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
    public function reset($token, Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'email' => 'email|required'
        ]);

        $status = Password::reset(array_merge($request->only('email', 'password'), compact('token')), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['status' => 'password-changed'], 200);
        }

        return response()->json(['error' => 'failed-password-change'], 400);
    }
}
