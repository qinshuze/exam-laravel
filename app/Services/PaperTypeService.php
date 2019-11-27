<?php


namespace App\Services;


use App\Models\PaperTypeModel;

class PaperTypeService
{

    public function getPageList(?array $condition = [], ?int $page = 1, ?int $size = 10)
    {
        $queryBuilder = PaperTypeModel::query();
        if (isset($condition['name'])) $queryBuilder->where('name', $condition['name']);
        $data = $queryBuilder->skip($page)->paginate($size);
        return $data;
    }
}