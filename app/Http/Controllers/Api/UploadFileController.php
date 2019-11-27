<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\UploadFile\UploadForm;
use App\Http\Resources\UploadFile\UploadFileResource;
use App\Services\UploadFileService;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class UploadFileController extends Controller
{
    /**
     * @var UploadFileService
     */
    private $uploadService;

    /**
     * UploadFileController constructor.
     * @param UploadFileService $uploadService
     */
    public function __construct(UploadFileService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function upload(UploadForm $form)
    {
        $file = $form->file('file');
        if (!$file->isValid()) throw new InternalErrorException('文件上传失败');
        $path = date('Ymd') .'/'. md5($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
        $uploadFileModel = $this->uploadService->uploadToLocal($file, $path);
        return ApiResponse::success(new UploadFileResource($uploadFileModel));
    }
}