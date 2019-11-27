<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserApplyModel
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property string $username 用户名
 * @property string $wechat 微信号
 * @property int $status 状态
 * @property string|null $phone 手机号
 * @property string $description 描述
 * @property int|null $approval_by 审批人
 * @property string|null $approval_at 审批时间
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereApprovalAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereApprovalBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserApplyModel whereWechat($value)
 * @mixin \Eloquent
 */
class UserApplyModel extends Model
{
    protected $table = 'user_apply';
    protected $fillable = [
        'user_id',
        'username',
        'wechat',
        'status',
        'phone',
        'description',
        'approval_by',
        'approval_at',
    ];
}