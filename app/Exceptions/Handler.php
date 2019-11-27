<?php

namespace App\Exceptions;

use App\Enums\ErrorCodeEnum;
use App\Helpers\ApiResponse;
use Config;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
    public function report(Exception $exception)
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
    public function render($request, Exception $exception)
    {
        if (!$request->is('api/*')) return parent::render($request, $exception);

        $info = null;
        if (Config::get('app.debug')) {
            $info = [
                $exception->getFile() . "({$exception->getLine()})",
                $exception->getTraceAsString()
            ];
        }

        $exceptionClass = get_class($exception);

        switch ($exceptionClass) {
            case NotFoundHttpException::class:
                return response()->json(ApiResponse::error($exception->getMessage(), ErrorCodeEnum::OBJECT_NOT_EXIST, $info));
            case BadRequestHttpException::class:
                return response()->json(ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST, $info));
            case ValidationException::class:
                return response()->json(ApiResponse::error(array_values($exception->errors())[0][0], Response::HTTP_NOT_ACCEPTABLE, $info));
            case InternalErrorException::class:
                return response()->json(ApiResponse::error($exception->getMessage(), Response::HTTP_NOT_ACCEPTABLE, $info));
            case UnauthorizedException::class:
                return response()->json(ApiResponse::error('未登录，请登录后再操作', Response::HTTP_UNAUTHORIZED, $info));
            case TokenExpiredException::class:
                return response()->json(ApiResponse::error('登录过期，请重新登录', Response::HTTP_UNAUTHORIZED, $info));
            case TokenInvalidException::class:
                return response()->json(ApiResponse::error('无法验证令牌签名', Response::HTTP_UNAUTHORIZED, $info));
            case JWTException::class:
                return response()->json(ApiResponse::error('无效的授权凭证', Response::HTTP_UNAUTHORIZED, $info));
            case AuthorizationException::class:
                return response()->json(ApiResponse::error($exception->getMessage(), Response::HTTP_FORBIDDEN, $info));
            default:
                return response()->json(ApiResponse::error($exception->getMessage(), $exception->getCode(), $info));
        }
    }
}
