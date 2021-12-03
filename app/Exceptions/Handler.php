<?php

namespace App\Exceptions;

use Throwable;
use ErrorException;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ZipCodeException;
use Illuminate\Database\QueryException;
use App\Http\Resources\ValidationResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        $this->reportable(function (Throwable $exception) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {


        DB::rollBack();

        if (($request->isJson() || $request->expectsJson() || $this->isInListException($exception)) && $this->isNotListException($exception)) {
            return $this->makeResponseJson($request, $exception);
        }

        if ($exception instanceof AuthenticationException && $this->isNotListException($exception)) {
            return redirect('/');
        }
        return parent::render($request, $exception);
    }

    public function isInListException($exception)
    {
        $classes =[
            HttpException::class,
            ErrorException::class,
            UnauthorizedHttpException::class,
            UnauthorizedHttpException::class,
            TokenBlacklistedException::class,
            MethodNotAllowedHttpException::class,
            ModelNotFoundException::class,
            NotFoundHttpException::class,
            ReflectionException::class,
            FilterDataValidationFailedException::class,
            QueryException::class,
            ValidationException::class,
            InvalidArgumentException::class,
            ZipCodeException::class
        ];

        foreach ($classes as $class) {
           if($exception instanceof $class) {
               return true;
            }
        }

        return false;
    }
    public function isNotListException($exception)
    {
        $classes =[];

        foreach ($classes as $class) {
           if($exception instanceof $class) {
               return false;
            }
        }

        return true;
    }


    public function makeResponseJson($request, $exception)
    {
        $production = config('sped.ambiente') == 'production';

        if ($exception instanceof ValidationException) { // Exception de validator
            return (new ValidationResource($exception))->additional([
                'success' => false
            ])->response();
        }

        $code = $this->getCodeException($exception);

        $headers = [];

        //Começo de toda exception inesperada
        $response = [
            'success' => false,
            'message' => $exception->getMessage()
        ];

        if ($exception instanceof HttpException) {
            $response['message'] = $exception->getMessage();
            $response['code'] = $exception->getStatusCode();
            $code = $exception->getStatusCode();
        }


        if ($exception instanceof ErrorException) {

            $response['url'] = $request->getUriForPath('/' . $request->path());
            $response['method'] = $request->getMethod();
        }

        if ($exception instanceof UnauthorizedHttpException) {
            $code = JsonResponse::HTTP_UNAUTHORIZED;
            $response['message'] = 'Usuário não autenticado';
            $response['error'] = 'usuario_nao_autenticado';
            $headers['WWW-Authenticate'] = 'jwt-auth';
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $code = JsonResponse::HTTP_BAD_REQUEST;
            $response['message'] = 'Método não permitido';
            $response['url'] = $request->getUriForPath('/' . $request->path());
            $response['method'] = $request->getMethod();
            $headers = $exception->getHeaders();
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = explode('\\', $exception->getModel());
            $response['message'] = "Recurso não encontrado";
            $response['data'] = end($model);
            $code = JsonResponse::HTTP_NOT_FOUND;
        }

        if ($exception instanceof NotFoundHttpException) {
            $response['message'] = "Recurso não encontrado";
            $response['url'] = $request->getUriForPath('/' . $request->path());
            $response['method'] = $request->getMethod();
            $code = JsonResponse::HTTP_NOT_FOUND;
            $headers = $exception->getHeaders();
        }

        if ($exception instanceof ReflectionException) {
            $response['message'] = "Erro Interno";
            $response['error'] = $exception->getMessage();
            $response['url'] = $request->getUriForPath('/' . $request->path());
            $response['method'] = $request->getMethod();
            $code = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($exception instanceof InvalidArgumentException) {
            $code = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        }

        //Exception para desenvolvimento
        if (!$production && $exception instanceof QueryException) {
            $code = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            $response['error'] = $exception->getPrevious()->getMessage();
            $response['query'] = $exception->getSql();
            $response['bindings'] = $exception->getBindings();
        }

        if (!$production && $code == JsonResponse::HTTP_INTERNAL_SERVER_ERROR) {
            $response['url'] = $request->getUriForPath('/' . $request->path());
            $response['method'] = $request->getMethod();
            $response['line'] = $exception->getLine();
            $response['file'] = $exception->getFile();
            $response['trace'] = $exception->getTrace();
        }

        return response()->json($response, $code, $headers);
    }



    public function getCodeException($exception)
    {
        return $exception->getCode() != 0 && !is_string($exception->getCode()) ? $exception->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
    }

}
