<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TopicModel
 *
 * @property int $id
 * @property int $topic_type_id 题目类型
 * @property string $content 题目内容
 * @property mixed $media 题目媒体资源
 * @property mixed $option 题目选项
 * @property mixed $answer 题目答案，非选项题的时候，需要填写此项
 * @property string $answer_analysis 题目答案解析
 * @property int $created_by 创建人ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereAnswerAnalysis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereTopicTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TopicModel extends Model
{
    protected $table = 'topic';
    protected $fillable = [
        'topic_type_id',
        'content',
        'media',
        'option',
        'answer',
        'answer_analysis',
        'created_by',
    ];
    protected $casts = [
        'media'  => 'array',
        'option' => 'array',
        'answer' => 'array',
    ];
}