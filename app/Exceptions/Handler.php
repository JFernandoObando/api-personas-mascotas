<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
      
        if ($request) {
            return $this->handleApiException($request, $exception);
        }
        return parent::render($request, $exception);
    }
    
    protected function handleApiException($request, Throwable $exception)
    {
        $status  = 500;
        $message = 'Ocurrió un error inesperado.';
    
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Datos inválidos.',
                'errors'  => $exception->errors(),
            ], 422);
        }
    
        if ($exception instanceof AuthenticationException) {
            $status  = 401;
            $message = 'No autenticado.';
        }
    
        if ($exception instanceof AuthorizationException) {
            $status  = 403;
            $message = 'No autorizado.';
        }
    
        if ($exception instanceof ModelNotFoundException) {
            $status  = 404;
            $message = 'Recurso no encontrado.';
        }
    
        if ($exception instanceof NotFoundHttpException) {
            $status  = 404;
            $message = 'Ruta no encontrada.';
        }
    
        return response()->json([
            'message'     => $message,
            'status_code' => $status,
            'exception'   => class_basename($exception),
        ], $status);
    }
    
}
