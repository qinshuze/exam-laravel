<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('oauth/wechat/login', 'Api\LoginController@wechatWebOauthLogin');        // 微信扫码登录
Route::post('oauth/wechat/applet/login', 'Api\LoginController@appletLogin');         // 微信小程序登录
Route::get('applet/example/paper', 'Api\PaperController@getExamplePageList');        // 获取示例考卷列表
Route::get('applet/paper/{id}', 'Api\UserAnswerController@getAnswerPaperDetail');    // 获取答卷详情

Route::namespace('Api')->middleware(\App\Http\Middleware\ApiAuthenticate::class)->group(function () {
    Route::get('dic', 'DicController@all');                 // 获取字典
    Route::get('dic/refresh', 'DicController@refresh');     // 刷新字典
    Route::post('upload', 'UploadFileController@upload');   // 上传文件
    Route::get('paper_type', 'PaperTypeController@list');   // 获取考卷类型列表
    Route::get('archives', 'ArchivesController@list');      // 获取考生档案

    Route::get('contact', 'AboutController@getContactDetails');      // 获取联系人
    Route::get('helper', 'AboutController@getHelperDoc');            // 获取帮助文档

    Route::prefix('user')->group(function () {
        Route::get('info', 'UserController@getUserInfo');                        // 获取当前用户信息
    });

    Route::middleware(\App\Http\Middleware\OpAuthenticate::class)->group(function () {
        Route::get('template/excel', 'AboutController@getExcelTemplate');
        Route::prefix('paper')->group(function () {
            Route::get('', 'PaperController@list');                     // 获取考卷列表
            Route::get('{id}', 'PaperController@detail');               // 获取考卷详情
            Route::post('', 'PaperController@create');                  // 创建考卷
            Route::put('{id}', 'PaperController@update');               // 更新考卷
            Route::delete('{id}', 'PaperController@delete');            // 删除考卷
            Route::post('{id}/clone', 'PaperController@clonePaper');    // 克隆考卷
        });
        Route::prefix('paper/{paper_id}/topic')->group(function () {
            Route::get('total/score', 'PaperTopicController@getTotalScore');                        // 获取考卷题目总分
            Route::get('', 'PaperTopicController@getTopicPageList');                                // 获取考卷题目分页列表
            Route::post('', 'PaperTopicController@saveTopic');                                      // 添加考卷题目
            Route::put('{topic_id}', 'PaperTopicController@saveTopic');                             // 更新考卷题目
            Route::get('amount', 'PaperTopicController@getTopicTypeQuantity');                      // 获取考卷题目类型数量
            Route::post('generate', 'PaperTopicController@generateRandomTopic');                    // 生成随机题目
            Route::post('{topic_id}/clone', 'PaperTopicController@cloneTopic');                     // 克隆题目
            Route::post('import/excel', 'PaperTopicController@importTopicByExcel');                 // 从excel文件导入题目
            Route::post('import', 'PaperTopicController@importTopicByPaper');                       // 从考卷导入题目
            Route::delete('', 'PaperTopicController@deleteTopic');                                  // 删除考卷题目
            Route::post('weight', 'PaperTopicController@updateTopicWeight');                        // 更新考卷题目权重
        });

        // 考卷申请
        Route::prefix('paper/{id}/apply')->group(function () {
            Route::get('', 'PaperApplyController@index');                                           // 获取考卷申请列表
            Route::post('{paperApplyId}/pass', 'PaperApplyController@pass');                        // 通过申请
            Route::post('{paperApplyId}/refuse', 'PaperApplyController@notPass');                   // 拒绝通过申请
            Route::post('{paperApplyId}/kickout', 'PaperApplyController@kickOut');                  // 踢出列表
        });

        Route::prefix('paper_config')->group(function () {
            Route::get('{paper_id}', 'PaperConfigController@detail');    // 获取考卷配置详情
            Route::put('{paper_id}', 'PaperConfigController@update');    // 更新考卷配置
        });

        Route::prefix('topic_type')->group(function () {
            Route::get('', 'TopicTypeController@list');                 // 获取题目类型列表
        });
    });

    Route::prefix('applet')->group(function () {
        Route::get('paper/{id}/topic', 'UserAnswerController@getAnswerPaper');   // 获取答卷
        Route::put('paper/{id}/topic', 'UserAnswerController@saveAnswerPaper');  // 保存答卷
        Route::put('paper/{id}/answer', 'UserAnswerController@startAnswer');     // 开始答题
        Route::post('paper/{id}/apply', 'PaperApplyController@apply');           // 答卷申请
        Route::post('user/apply/maker', 'UserApplyController@applyExaminer');     // 申请成为出题人
        Route::get('user/apply/maker', 'UserApplyController@getUserApplyStatus'); // 获取用户申请状态
        Route::post('phone', 'UserApplyController@getPhoneByWxEncryptedData');    // 获取用户微信关联手机号
        Route::post('submit_paper/{id}', 'UserAnswerController@submitAnswer');    // 交卷

        Route::get('history', 'UserAnswerHistoryController@list');                // 用户答题历史列表
        Route::get('history/{id}', 'UserAnswerHistoryController@detail');         // 用户答题历史详情

        Route::get('fav', 'UserPaperFavController@list');                         // 用户考卷收藏列表
        Route::post('fav', 'UserPaperFavController@add');                         // 添加收藏考卷
        Route::delete('fav/{id}', 'UserPaperFavController@delete');            // 删除收藏考卷
    });
});
