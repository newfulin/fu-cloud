<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 8:51
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgentUpgradeController extends Controller
{
    public function getRules()
    {
        return [
            'AgentUpgrade' => [
                'invite_code' => 'required'
            ],
            'GeneralAgentUpgrade' => [
                'invite_code' => 'required'
            ]
        ];
    }

    /**
     * @desc 代理商邀请码升级
     */
    public function AgentUpgrade(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info("代理商邀请码升级:|" . $user_id);
        Log::info("邀请码:|" . $request->input('invitation_code'));
        return Access::service('AgentUpgradeService')
            ->with('user_id',$user_id)
            ->with('invite_code',$request->input('invite_code'))
            ->run('AgentUpgrade');
    }
}