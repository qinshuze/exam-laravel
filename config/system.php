<?php
return [
    'paper' => [
        'official_example' => [1,2],     // 官方示例考卷id
        'default_front_cover' => '20191105/05a82d4d656e281c76dbc27e6e7f63c3.png',   // 默认考卷封面
    ],

    // 联系人配置
    'contact_information' => [
        'wechat'   => 'wx001',                  // 客服微信
        'contacts' => 'admin',                  // 联系人
        'op_url'   => 'http://op.zlwks.com'     // op后台地址
    ],

    // 物料配置
    'materials' => [
        'excel_template' => '/2de43616932193e7fe3fbcc42d5456d7.xlsx',                     // excel模板下载地址

        // 帮助文档
        'helper' => [
            [
                'name' => '如何创建试卷？',
                'url' => 'http://baidu.com'
            ]
        ]
    ],

    // 微信配置
    'wechat' => [
        // 开放平台
        'open_platform' => [
            'appid' => env('WECHAT_OPEN_PLATFORM_APPID'),
            'secret' => env('WECHAT_OPEN_PLATFORM_SECRET'),
            'token' => env('WECHAT_OPEN_PLATFORM_TOKEN'),
        ],

        // 小程序
        'applet' => [
            'appid' => env('WECHAT_APPLET_APPID'),
            'secret' => env('WECHAT_APPLET_SECRET'),
        ],
    ],
];

