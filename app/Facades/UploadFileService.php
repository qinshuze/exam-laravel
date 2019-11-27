<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class UploadFileService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UploadFileService';
    }
}