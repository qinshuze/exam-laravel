<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserAnswerHistoryModel
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $paper_id 考卷ID
 * @property mixed $content 用户作答内容
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerHistoryModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerHistoryModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerHistoryModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerHistoryModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerHistoryModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerHistoryModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerHistoryModel wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerHistoryModel whereUserId($value)
 * @mixin \Eloquent
 */
class UserAnswerHistoryModel extends Model
{
    const UPDATED_AT = null;

    protected $table = 'user_answer_history';
    protected $fillable = [
        'user_id',
        'paper_id',
        'content',
    ];
    protected $casts = [
        'content' => 'object',
    ];
}