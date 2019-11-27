<?php


namespace App\Enums;


class PaperEnum
{
    const STATUS_NOT_RELEASE        = 1;            // 未发布考卷
    const STATUS_RELEASE            = 2;            // 已发布考卷
    const STATUS_PAUSE              = 3;            // 暂停考卷
    const VISIT_RESTRICTION_DISABLE = 1;            // 禁用访问限制
    const VISIT_RESTRICTION_ENABLE  = 2;            // 启用访问限制
    const APPLY_STATUS_WAIT         = 1;            // 待审批
    const APPLY_STATUS_PASS         = 2;            // 审批通过
    const APPLY_STATUS_NOT_PASS     = 3;            // 审批未通过
    const MODE_BRUSH                = 1;            // 刷题模式
    const MODE_EXAM                 = 2;            // 考试模式
    const SCORE_TYPE_NOT            = 1;            // 无分数
    const SCORE_TYPE_CUSTOM         = 2;            // 自定义分数
    const SCORE_TYPE_STANDARD       = 3;            // 标准分数
    const ORGANIZATION_METHOD_ALL   = 1;            // 全题目
    const ORGANIZATION_METHOD_RAND  = 2;            // 随机抽题
}