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
                return response()->json(ApiResponse::error($exception->getMessage(), ErrorCodeEnum::NOTFOUND_NULL, $info), Response::HTTP_NOT_FOUND);
            case ValidationException::class:
                return response()->json(ApiResponse::error(array_values($exception->errors())[0][0], $exception->getCode(), $info), Response::HTTP_NOT_ACCEPTABLE);
            case UnauthorizedException::class:
                return response()->json(ApiResponse::error('未登录，请登录后再操作', ErrorCodeEnum::UNAUTHORIZED_NOT_LOGIN, $info), Response::HTTP_UNAUTHORIZED);
            case TokenExpiredException::class:
                return response()->json(ApiResponse::error('登录过期，请重新登录', ErrorCodeEnum::UNAUTHORIZED_TOKEN_EXPIRED, $info), Response::HTTP_UNAUTHORIZED);
            case TokenInvalidException::class:
                return response()->json(ApiResponse::error('无效的授权凭证', ErrorCodeEnum::UNAUTHORIZED_TOKEN_INVALID, $info), Response::HTTP_UNAUTHORIZED);
            case JWTException::class:
                return response()->json(ApiResponse::error('授权凭证验证失败', ErrorCodeEnum::CHECK_TOKEN_VERIFY_FAIL, $info), Response::HTTP_INTERNAL_SERVER_ERROR);
            case AuthorizationException::class:
                return response()->json(ApiResponse::error($exception->getMessage(), $exception->getCode(), $info), Response::HTTP_FORBIDDEN);
            default:
                return response()->json(ApiResponse::error($exception->getMessage(), $exception->getCode(), $info), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
