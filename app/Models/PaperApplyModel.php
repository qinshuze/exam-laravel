<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaperApplyModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $paper_id 考卷ID
 * @property int $status 状态
 * @property int $approved by 审批人
 * @property string $approval_at 审批时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel whereApprovalAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperApplyModel whereUserId($value)
 * @property int $approved by 审批人
 * @property int $approved by 审批人
 * @property int $approved by 审批人
 * @property int $approved by 审批人
 * @property int $approved by 审批人
 * @property int $approved by 审批人
 * @property int $approved by 审批人
 * @property int $approved by 审批人
 * @property int|null $approved by 审批人
 * @property int|null $approved by 审批人
 * @property int|null $approved by 审批人
 */
class PaperApplyModel extends Model
{
    protected $table = 'paper_apply';
    protected $fillable = [
        'user_id',
        'paper_id',
        'status',
        'approved_by',
        'approval_at',
    ];
}