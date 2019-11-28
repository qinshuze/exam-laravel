<?php

use Illuminate\Database\Seeder;

class DicTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dic')->delete();
        
        \DB::table('dic')->insert(array (
            0 => 
            array (
                'id' => 1,
                'en_name' => 'user_type',
                'cn_name' => '用户类型',
                'entry' => '[{"key": "company", "index": 1, "value": "企业", "is_default": true}, {"key": "individual", "index": 2, "value": "个体", "is_default": false}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'en_name' => 'user_status',
                'cn_name' => '用户状态',
                'entry' => '[{"key": "ok", "index": 1, "value": "正常", "is_default": false}, {"key": "pending", "index": 2, "value": "等待审核", "is_default": true}, {"key": "disable", "index": 3, "value": "禁用", "is_default": false}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'en_name' => 'user_auth_type',
                'cn_name' => '用户认证类型',
                'entry' => '[{"key": "local", "index": 1, "value": "本地", "is_default": false}, {"key": "wechat", "index": 2, "value": "微信", "is_default": true}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'en_name' => 'paper_is_show_result',
                'cn_name' => '是否显示结果',
                'entry' => '[{"key": "show", "index": 1, "value": "显示", "is_default": false}, {"key": "hide", "index": 2, "value": "隐藏", "is_default": true}]',
                'description' => '答题完成后是否允许答题人查看考试结果',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'en_name' => 'paper_mode',
                'cn_name' => '考试类型',
                'entry' => '[{"key": "brush_problem", "index": 1, "value": "刷题", "is_default": false}, {"key": "exam", "index": 2, "value": "考试", "is_default": true}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'en_name' => 'paper_organization_method',
                'cn_name' => '组卷方式',
                'entry' => '[{"key": "all", "index": 1, "value": "全题目", "is_default": true}, {"key": "random", "index": 2, "value": "随机抽题", "is_default": false}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'en_name' => 'paper_is_open',
                'cn_name' => '是否公开考卷',
                'entry' => '[{"key": "not", "index": 1, "value": "不公开", "is_default": true}, {"key": "open", "index": 2, "value": "公开", "is_default": false}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            7 => 
            array (
                'id' => 8,
                'en_name' => 'paper_is_allow_clone',
                'cn_name' => '是否允许克隆考卷',
                'entry' => '[{"key": "not", "index": 1, "value": "不允许", "is_default": true}, {"key": "allow", "index": 2, "value": "允许", "is_default": false}]',
                'description' => '是否允许克隆考卷',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            8 => 
            array (
                'id' => 9,
                'en_name' => 'paper_visit_restriction',
                'cn_name' => '是否启用访问限制',
                'entry' => '[{"key": "disable", "index": 1, "value": "禁用", "is_default": true}, {"key": "enable", "index": 2, "value": "启用", "is_default": false}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            9 => 
            array (
                'id' => 10,
                'en_name' => 'paper_score_config',
                'cn_name' => '试卷分数配置',
                'entry' => '[{"key": "no", "index": 1, "value": "无分数", "is_default": true}, {"key": "custom", "index": 2, "value": "自定义分数", "is_default": false}, {"key": "standard", "index": 3, "value": "标准分数", "is_default": false}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            10 => 
            array (
                'id' => 11,
                'en_name' => 'paper_apply_status',
                'cn_name' => '试卷申请状态',
                'entry' => '[{"key": "pending", "index": 1, "value": "待审批", "is_default": true}, {"key": "pass", "index": 2, "value": "已通过", "is_default": false}, {"key": "not_pass", "index": 3, "value": "未通过", "is_default": false}, {"key": "kick_out", "index": 4, "value": "已踢出", "is_default": false}, {"key": "ignore", "index": 5, "value": "忽略", "is_default": false}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            11 => 
            array (
                'id' => 12,
                'en_name' => 'paper_status',
                'cn_name' => '试卷状态',
                'entry' => '[{"key": "not_release", "index": 1, "value": "未发布", "is_default": true}, {"key": "release", "index": 2, "value": "已发布", "is_default": false}, {"key": "suspend", "index": 3, "value": "已暂停", "is_default": false}]',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
        ));
        
        
    }
}