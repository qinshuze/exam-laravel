<?php


namespace App\Enums;


class UserApplyEnum
{
    const STATUS_WAIT = 1;      // 等待审批
    const STATUS_PASS = 2;      // 审批通过
    const STATUS_NOT_PASS = 3;  // 审批未通过
}