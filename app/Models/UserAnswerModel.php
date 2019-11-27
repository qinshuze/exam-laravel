<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserAnswerModel
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $paper_id 考卷ID
 * @property mixed $content 用户作答内容
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel whereUserId($value)
 * @mixin \Eloquent
 * @property int $answer_frequency 答题次数
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel whereAnswerFrequency($value)
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAnswerModel whereUpdatedAt($value)
 */
class UserAnswerModel extends Model
{
    protected $table = 'user_answer';
    protected $fillable = [
        'user_id',
        'paper_id',
        'content',
    ];
    protected $casts = [
        'content' => 'object',
    ];
}