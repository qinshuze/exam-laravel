<?php


namespace App\Enums;


class ErrorCodeEnum
{
    const OBJECT_NOT_EXIST             = 10000;         // 查找的对象不存在或已被删除
    const PAPER_RELEASE_FAIL           = 10001;         // 考卷发布失败
    const USER_ANSWER_VALID_PERIOD_NOT = 20001;         // 用户答题时间不在考卷有效期内
    const USER_ANSWER_ALLOW_NOT        = 20002;         // 用户没有访问该考卷的权限
    const USER_ANSWER_FREQUENCY_MAX    = 20003;         // 用户已达到最大答题次数
    const USER_ANSWER_PASSWORD_NOT     = 20004;         // 需要输入密码才能继续答题
    const USER_ANSWER_PASSWORD_ERR     = 20005;         // 用户答题密码输入错误
    const USER_ANSWER_ALLOW_APPLY_NOT  = 20007;         // 用户没有申请答题
}