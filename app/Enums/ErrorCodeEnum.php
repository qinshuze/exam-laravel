<?php


namespace App\Enums;


class ErrorCodeEnum
{
    const UNAUTHORIZED_NOT_LOGIN     = 40001;       // 未登录
    const UNAUTHORIZED_TOKEN_EXPIRED = 40002;       // 授权令牌过期
    const UNAUTHORIZED_TOKEN_INVALID = 40003;       // 无效的授权令牌

    const VALIDATION_PARAM_NULL    = 40101;      // 缺少参数
    const VALIDATION_PARAM_TYPE    = 40102;      // 参数类型错误
    const VALIDATION_PARAM_UNIQUE  = 40103;      // 参数不是唯一的
    const VALIDATION_PARAM_INVALID = 40104;      // 参数无效

    const NOTFOUND_NULL = 40201;

    const AUTHORIZATION_USER_ANSWER_VALID_PERIOD_NOT = 40301;       // 用户答题不在考卷有效期内
    const AUTHORIZATION_USER_ANSWER_FREQUENCY_MAX    = 40302;       // 用户答题次数已达到最大
    const AUTHORIZATION_USER_ANSWER_PASSWORD_NOT     = 40303;       // 用户需要输入密码才能答题
    const AUTHORIZATION_USER_ANSWER_PASSWORD_ERR     = 40304;       // 用户答题密码输入错误
    const AUTHORIZATION_USER_ANSWER_UNAPPLY          = 40305;       // 用户还未申请答题
    const AUTHORIZATION_USER_ANSWER_APPLY_NOT_PASS   = 40307;       // 用户答题申请没有通过

    const CHECK_TOKEN_VERIFY_FAIL   = 50001;     // 授权令牌验证失败
    const CHECK_PAPER_COMPLETE_FAIL = 50101;     // 考卷完整性检查失败

//    const OBJECT_NOT_EXIST             = 10000;         // 查找的对象不存在或已被删除
//    const PAPER_RELEASE_FAIL           = 10001;         // 考卷发布失败
//    const USER_ANSWER_VALID_PERIOD_NOT = 20001;         // 用户答题时间不在考卷有效期内
//    const USER_ANSWER_ALLOW_NOT        = 20002;         // 用户没有访问该考卷的权限
//    const USER_ANSWER_FREQUENCY_MAX    = 20003;         // 用户已达到最大答题次数
//    const USER_ANSWER_PASSWORD_NOT     = 20004;         // 需要输入密码才能继续答题
//    const USER_ANSWER_PASSWORD_ERR     = 20005;         // 用户答题密码输入错误
//    const USER_ANSWER_ALLOW_APPLY_NOT  = 20007;         // 用户没有申请答题
}