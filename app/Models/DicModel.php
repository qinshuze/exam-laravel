<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DicModel
 *
 * @property int $id
 * @property string $en_name 字典英文名称
 * @property string $cn_name 字典中文名称
 * @property array $entry 字典条目
 * @property string $description 字典说明
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel whereCnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel whereEnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel whereEntry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DicModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DicModel extends Model
{
    protected $table = 'dic';
    protected $fillable = [
        'en_name',
        'cn_name',
        'entry',
        'description',
    ];
    protected $attributes = [
        'description' => '',
    ];
    protected $casts = [
        'entry' => 'array',
    ];
}