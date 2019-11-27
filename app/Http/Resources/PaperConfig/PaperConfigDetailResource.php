<?php


namespace App\Http\Resources\PaperConfig;


use Illuminate\Http\Resources\Json\JsonResource;

class PaperConfigDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $visit_url = '/api/applet/';
        return [
            'paper_id'               => $this->paper_id,
            'validity_period'        => $this->validity_period,
            'limited_time'           => $this->limited_time,
            'mode'                   => $this->mode,
            'organization_method'    => $this->organization_method,
            'is_show_result'         => $this->is_show_result,
            'pass_score'             => $this->pass_score,
            'answer_frequency'       => $this->answer_frequency,
            'is_open'                => $this->is_open,
            'is_allow_clone'         => $this->is_allow_clone,
            'visit_password'         => $this->visit_password,
            'visit_url'              => $visit_url,
            'applet_config'          => $this->applet_config,
            'visit_restriction'      => $this->visit_restriction,
            'description'            => $this->description,
            'score_config'           => $this->score_config,
            'topic_type_description' => $this->topic_type_description,
            'archives'               => $this->archives,
            'custom_archives'        => $this->custom_archives,
        ];
    }
}