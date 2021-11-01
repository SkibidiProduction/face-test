<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
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

    public function render($request, \Throwable $e)
    {
        if ($request->wantsJson()) {
            return $this->handleApiException($request, $e);
        } else {
            return parent::render($request, $e);
        }
    }

    /**
     * @param $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function handleApiException($request, Throwable $exception): JsonResponse
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    /**
     * @param $exception
     * @return JsonResponse
     */
    private function customApiResponse($exception): JsonResponse
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }
        if (method_exists($exception, 'getTrace')) {
            $trace = $exception->getTrace();
        } else {
            $trace = null;
        }

        $response = [
            'success' => false,
            'status' => $statusCode
        ];

        switch ($statusCode) {
            case 401: $response['message'] = 'Unauthorized'; break;
            case 403: $response['message'] = 'Forbidden'; break;
            case 404: $response['message'] = 'Not Found'; break;
            case 405: $response['message'] = 'Method Not Allowed'; break;
            case 422:
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            default:
                $response['message'] = ($statusCode == 500)
                    ? 'Service temporary unavailable'
                    : $exception->getMessage();
                break;
        }

        if (config('app.debug') && $trace) {
            $response['trace'] = $trace;
        }

        return response()->json($response, $statusCode);
    }
}
