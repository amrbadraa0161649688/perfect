<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use http\Exception\BadMethodCallException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            if ($exception instanceof NotFoundHttpException) {
                return responseFail('URL link not Found.', 404);
            }

            if ($exception instanceof BadMethodCallException) {
                return responseFail($exception->getMessage(), 405);

            }

            if ($exception instanceof ModelNotFoundException) {
                $exp = explode('\\', $exception->getMessage());
                $model_name = substr($exp[count($exp) - 1], 0, -3);
                return responseFail($model_name . ' not Found.', 404);
            }

            if ($exception instanceof AuthorizationException) {
                return responseFail($exception->getMessage(), 403);
            }
        }

        return parent::render($request, $exception);
    }
}
