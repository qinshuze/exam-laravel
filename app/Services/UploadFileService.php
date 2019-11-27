<?php


namespace App\Services;


use App\Models\UploadFileModel;
use Illuminate\Http\UploadedFile;

class UploadFileService
{
    /**
     * @var UploadFileModel
     */
    private $model;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UploadFileServiceImpl constructor.
     * @param UploadFileModel $model
     * @param UserService $userService
     */
    public function __construct(UploadFileModel $model, UserService $userService)
    {
        $this->model = $model;
        $this->userService = $userService;
    }


    /**
     * @param UploadedFile $file
     * @param string $path
     * @param string $disk
     * @return UploadFileModel
     * @throws \Exception
     */
    public function uploadToLocal(UploadedFile $file, string $path, string $disk = 'upload')
    {
        \Storage::disk($disk)->put($path, file_get_contents($file->getRealPath()));
        try {
            $model = new UploadFileModel();
            $model->name = $file->getClientOriginalName();
            $model->size = $file->getSize();
            $model->suffix = $file->getClientOriginalExtension();
            $model->path = $path;
            $model->created_by = $this->userService->getCurrentUserId();
            $model->save();

            return $model;
        } catch (\Exception $exception) {
            \Storage::delete($path);
            throw $exception;
        }
    }

    /**
     * 路径转url
     * @param string $path
     * @return string
     */
    public function pathToUrl(string $path): string
    {
        return \Storage::disk('upload')->url($path);
    }
}