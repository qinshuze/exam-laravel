<?php


namespace App\Http\Forms\PaperConfig;


use App\Enums\DicEnum;
use Illuminate\Foundation\Http\FormRequest;

class PaperConfigUpdateForm extends FormRequest
{
    public function rules()
    {
        return [
            'mode'                                              => ['integer', 'entry:' . DicEnum::PAPER_MODE],
            'is_show_result'                                    => ['integer', 'entry:' . DicEnum::PAPER_SHOW_RESULT],
            'is_allow_clone'                                    => ['integer', 'entry:' . DicEnum::PAPER_IS_ALLOW_CLONE],
            'visit_restriction'                                 => ['integer', 'entry:' . DicEnum::PAPER_VISIT_RESTRICTION],
            'limited_time'                                      => ['integer'],
            'pass_score'                                        => ['integer'],
            'answer_frequency'                                  => ['integer'],
            'validity_period'                                   => ['array'],
            'validity_period.status'                            => ['boolean'],
            'validity_period.start_time'                        => ['integer'],
            'validity_period.end_time'                          => ['required_with:validity_period.start_time', 'integer'],
            'organization_method'                               => ['array'],
            'organization_method.type'                          => ['integer', 'entry:' . DicEnum::PAPER_ORGANIZATION_METHOD],
            'organization_method.random'                        => ['array'],
            'organization_method.random.config'                 => ['array'],
            'organization_method.random.config.*.topic_type_id' => ['integer'],
            'organization_method.random.config.*.quantity'      => ['integer'],
            'organization_method.random.config.*.score'         => ['integer'],
            'organization_method.random.config.*.missing_score' => ['integer'],
            'organization_method.random.topic.*'                => ['integer'],
            'score_config'                                      => ['array'],
            'score_config.type'                                 => ['integer', 'entry:' . DicEnum::PAPER_SCORE_CONFIG],
            'score_config.topic.*'                              => ['integer'],
            'topic_type_description'                            => ['array'],
            'archives'                                          => ['array'],
            'archives.*.id'                                     => ['required_with:archives', 'integer'],
            'archives.*.title'                                  => ['required_with:archives'],
            'archives.*.type'                                   => ['required_with:archives'],
            'archives.*.value'                                  => ['array'],
            'archives.*.option'                                 => ['array'],
            'archives.*.is_edit'                                => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            'mode.integer'                           => '无效的参数：mode',
            'mode.entry'                             => '无效的参数：mode',
            'is_show_result.integer'                 => '无效的参数：is_show_result',
            'is_show_result.entry'                   => '无效的参数：is_show_result',
            'is_allow_clone.integer'                 => '无效的参数：is_allow_clone',
            'is_allow_clone.entry'                   => '无效的参数：is_allow_clone',
            'visit_restriction.integer'              => '无效的参数：visit_restriction',
            'visit_restriction.entry'                => '无效的参数：visit_restriction',
            'limited_time.integer'                   => '无效的参数：limited_time',
            'pass_score.integer'                     => '无效的参数：pass_score',
            'answer_frequency.integer'               => '无效的参数：answer_frequency',
            'validity_period.array'                  => '无效的参数：validity_period',
            'validity_period.status.boolean'         => '无效的参数：validity_period[status]',
            'validity_period.start_time.time'        => '无效的参数：validity_period[start_time]',
            'validity_period.end_time.time'          => '无效的参数：validity_period[end_time]',
            'validity_period.end_time.required_with' => 'validity_period[end_time] 不能为空',
        ];
    }
}