<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 9:57
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InviteCodeController extends Controller
{
    public function getRules()
    {
        return [
            'useCode' => [
                'code' => 'required|desc:邀请码',
            ],
            'giveInviteCode' => [
                'code' => 'required|desc:邀请码',
                'tel' => 'required|mobile|desc:被转赠人手机号'
            ],
            'giveInviteList' => [
                'level' => 'desc:P1201：VIP  、P1301：总代理 、 P1201：合伙人',
                'page' => '',
                'pageSize' => ''
            ],

            'oldUserUpgrade' => [
                'invite_code' => 'required|desc:邀请码',
                'tel' => 'required|desc:用户手机号',
                'level' => 'required|desc:当前用户等级'
            ],
            'getOldUserInfo' => [
                'invite_code' => 'required|desc:邀请码',
            ],

        ];
    }

    /**
     * @desc 使用邀请码
     */
    public function useCode(Request $request)
    {
//        $userId = "1136660070428347392";
        $userId = $request->user()->claims->getId();
        Log::info('获取用户信息————————'.$userId);

        return Access::service('InviteCodeService')
            ->with('userId',$userId)
            ->with('code',$request->input('code'))
            ->run('useCode');
    }

    /**
     * @desc 邀请码的转赠
     */
    public function giveInviteCode(Request $request)
    {
//        $userId = "1090384700536765952";
        $userId = $request->user()->claims->getId();
        Log::info('邀请码的转赠，用户信息-------'.$userId);

        return Access::service('InviteCodeService')
            ->with('userId',$userId)
            ->with('code',$request->input('code'))
            ->with('tel',$request->input('tel'))
            ->run('giveInviteCode');

    }

    /**
     * @desc 获取邀请码列表
     */
    public function giveInviteList(Request $request)
    {
        $userId = $request->user()->claims->getId();
        Log::info('获取邀请码页面，用户信息-------'.$userId);

        return Access::service('InviteCodeService')
            ->with('level',$request->input('level'))
            ->with('user_id',$userId)
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('giveInviteList');
    }

    /**
     * @desc 获取原六个车用户信息
     */
    public function getOldUserInfo(Request $request)
    {
        $userId = $request->user()->claims->getId();
        Log::info('获取原六个车用户信息，用户信息-------'.$userId);

        return Access::service('InviteCodeService')
            ->with('userId',$userId)
            ->with('invite_code',$request->input('invite_code'))
            ->run('getOldUserInfo');
    }

    /**
     * @desc 原六个车用户邀请码升级  六个车合伙人  合作商|车巢|代理商
     */
    public function oldUserUpgrade(Request $request)
    {
        Log::info("原六个车用户邀请码升级  六个车合伙人  合作商|车巢|代理商");

        return Access::service('InviteCodeService')
            ->with('invite_code',$request->input('invite_code'))
            ->with('tel',$request->input('tel'))
            ->with('old_level',$request->input('level'))
            ->run('oldUserUpgrade');
    }
}