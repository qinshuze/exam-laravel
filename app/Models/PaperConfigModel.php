<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaperConfigModel
 *
 * @property int $paper_id
 * @property int $mode 考卷模式
 * @property int $is_show_result 答题完成后是否允许答题人查看考试结果
 * @property int $is_open 是否公开，公开后所有人都能看到
 * @property int $is_allow_clone 是否允许克隆考卷
 * @property int $visit_restriction 是否启用访问限制
 * @property string $visit_password 访问密码(为空则表示不启用密码访问)
 * @property int $limited_time 答题时长(为0则不限制)
 * @property int $pass_score 及格分数(为0则不限制)
 * @property int $answer_frequency 每个用户的答题次数(为0则表示不限制)
 * @property mixed $validity_period 考卷有效期
 * @property mixed $organization_method 组卷方式
 * @property mixed $applet_config 小程序配置
 * @property mixed $score_config 分数设置
 * @property mixed $topic_type_description 题型说明
 * @property mixed $archives 考生档案
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereAnswerFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereAppletConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereArchives($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereIsAllowClone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereIsShowResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereLimitedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereOrganizationMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel wherePassScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereScoreConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereTopicTypeDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereValidityPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereVisitPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereVisitRestriction($value)
 * @mixin \Eloquent
 * @property mixed|null $custom_archives 用户自定义档案
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperConfigModel whereCustomArchives($value)
 */
class PaperConfigModel extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $table      = 'paper_config';
    protected $primaryKey = 'paper_id';
    protected $fillable   = [
        'paper_id',
        'mode',
        'is_show_result',
        'is_open',
        'is_allow_clone',
        'visit_restriction',
        'visit_password',
        'limited_time',
        'pass_score',
        'answer_frequency',
        'validity_period',
        'organization_method',
        'applet_config',
        'score_config',
        'topic_type_description',
        'archives',
        'custom_archives',
    ];
    protected $casts      = [
        'validity_period'        => 'object',
        'applet_config'          => 'object',
        'score_config'           => 'object',
        'topic_type_description' => 'object',
        'organization_method'    => 'object',
        'archives'               => 'array',
        'custom_archives'        => 'array',
    ];
}