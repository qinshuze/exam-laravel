<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BannerModel
 *
 * @property int $id
 * @property int $type 轮播图类型
 * @property string $path 轮播图路径
 * @property int $weight 权重
 * @property int|null $width 轮播图宽
 * @property int|null $height 轮播图高
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BannerModel whereWidth($value)
 * @mixin \Eloquent
 */
class BannerModel extends Model
{
    protected $table = 'banner';
    protected $fillable = [
        'type',
        'path',
        'weight',
        'width',
        'height',
    ];
}