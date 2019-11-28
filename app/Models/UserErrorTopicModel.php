<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserErrorTopicModel
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $paper_id 考卷ID
 * @property mixed $content 内容
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserErrorTopicModel whereUserId($value)
 * @mixin \Eloquent
 */
class UserErrorTopicModel extends Model
{
    protected $table = 'user_error_topic';
    protected $fillable = [
        'user_id',
        'paper_id',
        'content',
    ];
    protected $casts = [
        'content' => 'object'
    ];
}