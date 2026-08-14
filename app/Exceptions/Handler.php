<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        $isCsrfException = $exception instanceof \Illuminate\Session\TokenMismatchException
            || ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() === 419);

        if ($isCsrfException) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message'  => 'Tu sesión ha expirado por inactividad. Redireccionando al inicio de sesión...',
                    'redirect' => route('login'),
                ], 401);
            }
            return redirect()->route('login')->with('warning', 'Tu sesión ha expirado por inactividad. Por favor, iniciá sesión nuevamente.');
        }

        return parent::render($request, $exception);
    }
}
