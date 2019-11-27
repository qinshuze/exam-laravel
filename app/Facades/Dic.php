<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Dic extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Dic';
    }
}