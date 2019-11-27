<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
/**
 * App\Models\WeChatAuthModel
 *
 * @property int $id
 * @property int $user_id
 * @property string $unionid
 * @property string $access_token
 * @property string $openid
 * @property string $session_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel whereSessionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel whereUnionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatAuthModel whereUserId($value)
 * @mixin \Eloquent
 */
class WeChatAuthModel extends Model
{
    protected $table = 'wechat_auth';
    protected $fillable = [
        'user_id',
        'unionid',
        'access_token',
        'openid',
        'session_key',
    ];
}