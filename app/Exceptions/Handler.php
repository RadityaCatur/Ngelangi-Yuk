<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    /**
     * Override render to catch expired sessions or CSRF token errors
     */
    public function render($request, Throwable $exception)
    {
        // ✅ Token mismatch (CSRF expired)
        if ($exception instanceof TokenMismatchException) {
            return redirect()
                ->route('login') // route login kamu valid karena Auth::routes() sudah aktif
                ->with('message', 'Sesi kamu sudah habis, silakan login ulang ya ✨');
        }

        // ✅ Session auth expired / belum login
        if ($exception instanceof AuthenticationException) {
            return redirect()
                ->route('login')
                ->with('message', 'Sesi login kamu sudah habis, silakan masuk lagi 🙏');
        }

        return parent::render($request, $exception);
    }
}
