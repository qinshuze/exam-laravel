<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TopicTypeModel
 *
 * @property int $id
 * @property string $name 类型名称
 * @property string $description 描述
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicTypeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicTypeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicTypeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicTypeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicTypeModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicTypeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicTypeModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TopicTypeModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TopicTypeModel extends Model
{
    protected $table = 'topic_type';
}