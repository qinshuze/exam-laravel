<?php


namespace App\Models;


use App\Scopes\CreatedByScope;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaperTopicModel
 *
 * @property int $id
 * @property int $paper_id 考卷ID
 * @property int $topic_id 题目ID
 * @property int $score 题目分数
 * @property int $weight 权重
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperTopicModel whereWeight($value)
 * @mixin \Eloquent
 */
class PaperTopicModel extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $table = 'paper_topic';
    protected $fillable = [
        'paper_id',
        'topic_id',
        'score',
        'weight',
    ];
}