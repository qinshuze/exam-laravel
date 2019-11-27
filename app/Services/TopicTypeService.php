<?php


namespace App\Services;


use App\Models\TopicTypeModel;

class TopicTypeService
{
    /**
     * 获取考卷分页列表
     * @param array|null $condition
     * @param int|null $page
     * @param int|null $size
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPageList(?array $condition, ?int $page, ?int $size)
    {
        $queryBuilder = TopicTypeModel::query();
        if (isset($condition['name'])) $queryBuilder->whereName($condition['name']);
        $paginate  = $queryBuilder->skip($page)->paginate($size);
        return $paginate;
    }
}