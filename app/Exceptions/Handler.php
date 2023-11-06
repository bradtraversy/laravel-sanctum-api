<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (Exception $e, $request) {
            if ($request->is('api/*')) {
                $message = "Something went wrong";
                $status = 500;
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    $message = "Not authenticated";
                    $status = 401;
                } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                    $message = $e->validator->errors();
                    $status = 403;
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    $message = 'Resource not found';
                    $status = 404;
                }
                return response()->json([
                    'message' => $message
                ], $status);
            }
        });
    }
}
