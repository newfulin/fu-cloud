<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 15:03
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedbackMessageController extends Controller
{
    public function getRules()
    {
        return [
            'setFeedbackInfo' => [
                'basicInfo' => 'required',
                'content' => 'required'
            ]
        ];
    }

    /**
     * @desc 信息反馈
     * */
    public function setFeedbackInfo(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("消息反馈:|" . $user_id);

        return Access::service('FeedbackMessageService')
            ->with('user_id',$user_id)
            ->with('basicInfo',$request->input('basicInfo'))
            ->with('content',$request->input('content'))
            ->run('setFeedbackInfo');
    }
}