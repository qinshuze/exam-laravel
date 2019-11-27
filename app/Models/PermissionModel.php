<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PermissionModel
 *
 * @property int $id
 * @property int $pid 父ID
 * @property string $title 标题
 * @property string $route 资源路由
 * @property string $icon 图标
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PermissionModel extends Model
{
    protected $table = 'permission';
    protected $fillable = [
        'pid',
        'title',
        'route',
        'icon',
    ];
    protected $attributes = [
        'icon' => '',
    ];
}