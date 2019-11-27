<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserInfoModel
 *
 * @property int $user_id
 * @property string|null $wx_phone 微信绑定的手机号
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfoModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfoModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfoModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfoModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfoModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfoModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfoModel whereWxPhone($value)
 * @mixin \Eloquent
 */
class UserInfoModel extends Model
{
    protected $table = 'user_info';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_id',
        'wx_phone',
    ];
}