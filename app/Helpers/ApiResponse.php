<?php


namespace App\Helpers;


use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function success($data = [], $message = 'success', $code = Response::HTTP_OK)
    {
        return self::json($code, $message, $data);
    }

    public static function message($message = 'success', $code = Response::HTTP_OK)
    {
        return self::json($code, $message, []);
    }

    public static function error($message, $code, $data = [])
    {
        return self::json($code, $message, $data);
    }

    public static function json($code, $message, $data = [])
    {
        $resource = ['code' => $code, 'message' => $message, 'data' => $data];
        if ($data instanceof AnonymousResourceCollection) {
            $resource['data'] = $data->resource->items();
            $resource['paginate']['page'] = $data->resource->currentPage();
            $resource['paginate']['size'] = $data->resource->perPage();
            $resource['paginate']['total'] = $data->resource->total();
        }

        return $resource;
    }
}