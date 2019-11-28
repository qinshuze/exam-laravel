<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserPaperFavModel
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $paper_id 考卷id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPaperFavModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPaperFavModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPaperFavModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPaperFavModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPaperFavModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPaperFavModel wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPaperFavModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPaperFavModel whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\PaperModel $paper
 */
class UserPaperFavModel extends Model
{
    protected $table = 'user_paper_fav';
    protected $fillable = [
        'user_id',
        'paper_id',
    ];

    public function paper()
    {
        return $this->belongsTo(PaperModel::class);
    }
}