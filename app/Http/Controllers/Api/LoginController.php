<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\Login\AppletLoginForm;
use App\Http\Forms\Login\WechatWebOauthLoginForm;
use App\Services\UserService;
use App\Services\WeChatService;
use Illuminate\Auth\AuthenticationException;

class LoginController extends Controller
{
    /**
     * @var WeChatService
     */
    private $wechatService;
    /**
     * @var UserService
     */
    private $userService;

    /**
     * LoginController constructor.
     * @param WeChatService $wechatService
     * @param UserService $userService
     */
    public function __construct(WeChatService $wechatService, UserService $userService)
    {
        $this->wechatService = $wechatService;
        $this->userService   = $userService;
    }

    public function appletLogin(AppletLoginForm $form)
    {
        $config = config('system.wechat.applet');
        $authorization = $this->wechatService->getAppletAuthorization($form->input('code'), $config);
        $wxUserInfo = $this->wechatService->decryptData($config['appid'],$form->input('encrypted_data'), $form->input('iv'), $authorization->session_key);
        $wechatAuthModel = $this->wechatService->getByUnionid($wxUserInfo->unionId);
        if (!$wechatAuthModel) {
            $userInfo = [
                'nickname' => $wxUserInfo->nickName,
                'avatar' => $wxUserInfo->aratarUrl,
                'unionid' => $wxUserInfo->unionId,
                'openid' => $wxUserInfo->openId,
                'session_key' => $authorization->session_key,
            ];
            $userModel = $this->userService->registerByWechat($userInfo);
        } else {
            $wechatAuthModel->session_key = $authorization->session_key;
            $wechatAuthModel->update();
            $userModel = $this->userService->getById($wechatAuthModel->user_id);
        }

        $token = \JWTAuth::fromUser($userModel);

        return ApiResponse::success([
            'id' => $userModel->id,
            'nickname' => $userModel->nickname,
            'avatar' => $userModel->avatar,
            'token' => $token,
        ]);
    }

    public function wechatWebOauthLogin(WechatWebOauthLoginForm $form)
    {
        $config = config('system.wechat.open_platform');
        $authorization = $this->wechatService->getOpenPlatformAuthorization($form->input('code'), $config);
        $wxUserInfo = $this->wechatService->getWxUserInfo($authorization->access_token, $authorization->openid);
        $wechatAuthModel = $this->wechatService->getByUnionid($wxUserInfo->unionid);
        if (!$wechatAuthModel) {
            $userInfo = [
                'nickname' => $wxUserInfo->nickname,
                'avatar' => $wxUserInfo->headimgurl,
                'unionid' => $wxUserInfo->unionid,
                'access_token' => $authorization->access_token,
                'openid' => $wxUserInfo->openid,
            ];
            $userModel = $this->userService->registerByWechat($userInfo);
        } else {
            $wechatAuthModel->access_token = $authorization->access_token;
            $wechatAuthModel->update();
            $userModel = $this->userService->getById($wechatAuthModel->user_id);
        }

        $token = \JWTAuth::fromUser($userModel);
        if (!$userModel->hasRole(['examiner'])) {
            throw new AuthenticationException('当前用户没有访问权限');
        }

        return ApiResponse::success([
            'id' => $userModel->id,
            'nickname' => $userModel->nickname,
            'avatar' => $userModel->avatar,
            'token' => $token,
        ]);
    }
}