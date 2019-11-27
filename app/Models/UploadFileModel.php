<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UploadFileModel
 *
 * @property int $id
 * @property string $name 文件原名
 * @property string $path 文件存储路径
 * @property string $suffix 文件后缀名
 * @property int $size 文件大小[bit]
 * @property int $created_by 上传人
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadFileModel whereSuffix($value)
 * @mixin \Eloquent
 */
class UploadFileModel extends Model
{
    const UPDATED_AT = null;

    protected $table = 'upload_file';
    protected $fillable = [
        'name',
        'path',
        'suffix',
        'size',
        'created_by',
    ];
}