<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaperTypeModel
 *
 * @property int $id
 * @property string $name 类型名称
 * @property string $description 类型描述
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTypeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTypeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTypeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTypeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTypeModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTypeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTypeModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTypeModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaperTypeModel extends Model
{
    protected $table = 'paper_type';
    protected $fillable = [
        'name',
        'description',
    ];
}