<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\UserApply\UserApplyEncryptedDataForm;
use App\Http\Forms\UserApply\UserApplyExaminerForm;
use App\Http\Resources\UserApply\UserApplyDetailResource;
use App\Services\UserApplyService;
use App\Services\UserInfoService;
use App\Services\WeChatService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserApplyController extends Controller
{
    /**
     * @var UserApplyService
     */
    private $userApplyService;
    /**
     * @var WeChatService
     */
    private $wechatService;
    /**
     * @var UserInfoService;
     */
    private $userInfoService;

    /**
     * UserApplyController constructor.
     * @param UserApplyService $userApplyService
     * @param WeChatService $wechatService
     * @param UserInfoService $userInfoService
     */
    public function __construct(UserApplyService $userApplyService, WeChatService $wechatService, UserInfoService $userInfoService)
    {
        $this->userApplyService = $userApplyService;
        $this->wechatService    = $wechatService;
        $this->userInfoService  = $userInfoService;
    }


    public function applyExaminer(UserApplyExaminerForm $form)
    {
        $this->userApplyService->applyExaminer(\UserService::getCurrentUserId(), $form->input());
        return ApiResponse::success(config('system.contact_information', []));
    }

    public function getUserApplyStatus()
    {
        $userApplyModel = $this->userApplyService->getByUserId(\UserService::getCurrentUserId());
        if (!$userApplyModel) throw new NotFoundHttpException('系统找不到申请记录');
        return ApiResponse::success(new UserApplyDetailResource($userApplyModel));
    }

    public function getPhoneByWxEncryptedData(UserApplyEncryptedDataForm $form)
    {
        $input = $form->input();
        $config = config('system.wechat.applet');
        $wechatAuthModel = $this->wechatService->getByUserId(\UserService::getCurrentUserId());
        if (!$wechatAuthModel) throw new NotFoundHttpException('找不到用户授权信息');
        $userInfoModel = $this->userInfoService->getByUserId(\UserService::getCurrentUserId());

        try {
            $data = $this->wechatService->decryptData($config['appid'], $input['encrypted_data'], $input['iv'], $wechatAuthModel->session_key);
            if (!$userInfoModel) {
                $this->userInfoService->create(\UserService::getCurrentUserId(), ['wx_phone' => $data->phoneNumber]);
            } else {
                $this->userInfoService->update(\UserService::getCurrentUserId(), ['wx_phone' => $data->phoneNumber]);
            }
            return ApiResponse::success(['phone' => $data->phoneNumber]);
        } catch (\Exception $exception) {
            if (!$userInfoModel || $userInfoModel->wx_phone) throw $exception;
            return ApiResponse::success(['phone' => $userInfoModel->wx_phone]);
        }
    }
}