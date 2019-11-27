<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RoleModel
 *
 * @property int $id
 * @property string $name 角色名称
 * @property string $description 角色描述
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoleModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoleModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoleModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoleModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoleModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RoleModel extends Model
{
    protected $table = 'role';
    protected $fillable = [
        'name',
        'description',
    ];
    protected $attributes = [
        'description' => '',
    ];
}