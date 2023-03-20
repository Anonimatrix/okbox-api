<?php

namespace App\Exceptions;

use Exception;
use Google\Service\Exception as ServiceException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ServiceException $e) {
            $errors = $e->getErrors();
            $message = count($errors) > 0 ? ((object) $errors[0])->message : $e->getMessage();
            return response()->json(['message' => $message, "code" => $e->getCode()]);
        });

        $this->renderable(function (HttpException $e) { 
            $code = $e->getStatusCode();
            $statusCode = $code;
            $message = $e->getMessage() ? $e->getMessage() : "Error " . $code;
            return response()->json(compact('message', 'code', 'statusCode'));
        });

        $this->renderable(function (Exception $e) {
            if(env("APP_ENV") === 'local') dd($e);
            return response()->json(['message' => $e->getMessage(), "status" => "Error"]);
        });
    }
}
