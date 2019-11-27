<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RolePermissionModel
 *
 * @property int $id
 * @property int $role_id 角色ID
 * @property int $permission_id 权限ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionModel wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionModel whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RolePermissionModel extends Model
{
    protected $table = 'role_permission';
    protected $fillable = [
        'role_id',
        'permission_id',
    ];
}