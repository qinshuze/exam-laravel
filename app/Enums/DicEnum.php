<?php


namespace App\Enums;


class DicEnum
{
    const PAPER_MODE = 'paper_mode';
    const PAPER_MODE_BRUSH = 'brush_problem';
    const PAPER_MODE_EXAM = 'exam';

    const PAPER_SHOW_RESULT = 'paper_is_show_result';

    const PAPER_IS_OPEN = 'paper_is_open';

    const PAPER_IS_ALLOW_CLONE = 'paper_is_allow_clone';
    const PAPER_IS_ALLOW_CLONE_ALLOW = 'allow';
    const PAPER_IS_ALLOW_CLONE_NOT = 'not';

    const PAPER_VISIT_RESTRICTION = 'paper_visit_restriction';
    const PAPER_VISIT_RESTRICTION_ENABLE = 'enable';

    const PAPER_ORGANIZATION_METHOD = 'paper_organization_method';
    const PAPER_ORGANIZATION_METHOD_RAND = 'random';
    const PAPER_ORGANIZATION_METHOD_ALL = 'all';

    const PAPER_SCORE_CONFIG = 'paper_score_config';
    const PAPER_SCORE_CONFIG_NOT = 'not';
    const PAPER_SCORE_CONFIG_STANDARD = 'standard';
    const PAPER_SCORE_CONFIG_CUSTOM = 'custom';

    const PAPER_STATUS = 'paper_status';
    const PAPER_STATUS_PAUSE = 'pause';
    const PAPER_STATUS_RELEASE = 'release';
    const PAPER_STATUS_NOT_RELEASE = 'not_release';

    const PAPER_APPLY_STATUS = 'paper_apply_status';
    const PAPER_APPLY_STATUS_PASS = 'pass';
    const PAPER_APPLY_STATUS_NOT_PASS = 'not_pass';
    const PAPER_APPLY_STATUS_PENDING = 'pending';
    const PAPER_APPLY_STATUS_KICK_OUT = 'kick_out';
    const PAPER_APPLY_STATUS_IGNORE = 'ignore';
}