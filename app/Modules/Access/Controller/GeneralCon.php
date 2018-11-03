<?php

namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GeneralCon extends Controller
{

    public function getRules()
    {
        return [
            'feedback' => [
                'basicInfo' => 'required',// 基本反馈
                'content' => 'required', // 内容
            ],

        ];
    }

    /**
     * @desc 反馈
     */
    public function feedback(Request $request)
    {
        $userId = $request->user()->claims->getId();
        $ret = Access::service('GeneralSer')
            ->with('basicInfo', $request->input('basicInfo'))
            ->with('content', $request->input('content'))
            ->with('userId', $userId)
            ->run('feedback');
        return $ret;
    }
}
