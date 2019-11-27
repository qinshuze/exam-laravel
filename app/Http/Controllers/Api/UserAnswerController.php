<?php


namespace App\Http\Controllers\Api;


use App\Enums\ErrorCodeEnum;
use App\Enums\PaperEnum;
use App\Facades\UploadFileService;
use App\Helpers\ApiResponse;
use App\Http\Forms\UserAnswer\UserAnswerPaperForm;
use App\Http\Forms\UserAnswer\UserAnswerPaperSaveForm;
use App\Http\Resources\UserAnswer\UserAnswerPaperResource;
use App\Models\PaperConfigModel;
use App\Models\PaperModel;
use App\Models\UserAnswerModel;
use App\Services\PaperConfigService;
use App\Services\PaperService;
use App\Services\UserAnswerService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserAnswerController
{
    /**
     * @var UserAnswerService
     */
    private $userAnswer;
    /**
     * @var PaperConfigService
     */
    private $paperConfigService;
    /**
     * @var PaperService
     */
    private $paperService;

    /**
     * UserAnswerController constructor.
     * @param UserAnswerService $userAnswer
     * @param PaperConfigService $paperConfigService
     * @param PaperService $paperService
     */
    public function __construct(UserAnswerService $userAnswer, PaperConfigService $paperConfigService, PaperService $paperService)
    {
        $this->userAnswer         = $userAnswer;
        $this->paperConfigService = $paperConfigService;
        $this->paperService       = $paperService;
    }

    public function getAnswerPaper(int $paper_id, UserAnswerPaperForm $form)
    {
        $paperModel = PaperModel::query()->find($paper_id);
        if (!$paperModel) throw new NotFoundHttpException('该考试已被出题人删除');
        $paperConfigModel = PaperConfigModel::query()->find($paper_id);
        if ($paperConfigModel->visit_password != '') {
            if (!$form->input('password')) {
                return ApiResponse::error('该考试需要输入密码才能进入', ErrorCodeEnum::USER_ANSWER_PASSWORD_NOT);
            } else if ($paperConfigModel->visit_password != $form->input('password')) {
                return ApiResponse::error('密码输入错误', ErrorCodeEnum::USER_ANSWER_PASSWORD_ERR);
            }
        }

        $this->userAnswer->check(\UserService::getCurrentUserId(), $paperModel);
        $answerPaper = $this->userAnswer->getAnswerPaper($paper_id);
        return ApiResponse::success(new UserAnswerPaperResource($answerPaper));
    }

    public function getAnswerPaperDetail(int $paper_id)
    {
        $paperModel = PaperModel::query()->withoutGlobalScopes()->find($paper_id);
        if (!$paperModel) throw new NotFoundHttpException('该考试已被出题人删除');
        $answerPaperTemp = \Storage::disk('paper')->get($paper_id . '.json');
        $answerPaperTemp = json_decode($answerPaperTemp);

        $paperConfigModel   = $this->paperConfigService->getByPaperId($paper_id);
        $userAnswerPaginate = UserAnswerModel::query()
            ->join('users', 'users.id', '=', 'user_answer.user_id')
            ->wherePaperId($paper_id)
            ->selectRaw('users.id,users.nickname,users.avatar')
            ->paginate(8);

        $front_cover = $paperModel->front_cover ? $paperModel->front_cover : config('system.paper.default_front_cover');
        $data        = [
            'id'              => $paperModel->id,
            'title'           => $paperModel->title,
            'front_cover'     => \UploadFileService::pathToUrl($front_cover),
            'limited_time'    => $paperConfigModel->limited_time,
            'topic_quantity'  => $answerPaperTemp->mode == PaperEnum::MODE_BRUSH ? 15 : count($answerPaperTemp->topic),
            'total_score'     => $answerPaperTemp->total_score,
            'validity_period' => $paperConfigModel->validity_period->status ? [$paperConfigModel->validity_period->start_time, $paperConfigModel->validity_period->end_time] : [],
            'collect'         => 1,
            'mode'            => $answerPaperTemp->mode,
            'participant'     => [
                'total' => $userAnswerPaginate->total(),
                'users' => $userAnswerPaginate->items(),
            ],
        ];

        return ApiResponse::success($data);
    }

    public function saveAnswerPaper(int $paper_id, UserAnswerPaperSaveForm $form)
    {
        $input             = $form->input();
        $userAnswer = $this->userAnswer->getUserAnswer(\UserService::getCurrentUserId(), $paper_id);
        if (!$userAnswer) {
            $this->userAnswer->create($paper_id, \UserService::getCurrentUserId(), $input);
        } else {
            $this->userAnswer->update($paper_id, \UserService::getCurrentUserId(), $input);
        }

        return ApiResponse::success();
    }

    public function startAnswer(int $paper_id)
    {
        $this->userAnswer->startAnswer(\UserService::getCurrentUserId(), $paper_id);
        return ApiResponse::success();
    }

    public function submitAnswer(int $paper_id)
    {
        $answer = $this->userAnswer->submitAnswerPaper(\UserService::getCurrentUserId(), $paper_id);
        return ApiResponse::success([
            'correct_rate'      => $answer->content->correct_rate,
            'error_rate'        => $answer->content->error_rate,
            'answer_time'       => $answer->content->answer_time,
            'submit_paper_time' => $answer->content->submit_paper_time,
        ]);
    }
}