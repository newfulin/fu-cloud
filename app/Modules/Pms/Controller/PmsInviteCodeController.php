<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 10:57
 */

namespace App\Modules\Pms\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Pms\Pms;
use Illuminate\Support\Facades\Log;
use Unirest\Request;

class PmsInviteCodeController extends Controller
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc 六个车合伙人升升级区代使用的邀请码
     */
    public function pmsPartnerInviteCode(Request $request)
    {
        Log::info('六个车合伙人升升级区代使用的邀请码');

        return Pms::service('PmsInviteCodeService')
            ->run('pmsPartnerInviteCode');
    }

    /**
     * @desc //六个车合作商，升级PMS生成外部的邀请码
     */
    public function pmsOperatorInviteCode(Request $request)
    {
        Log::info('六个车合伙人升升级区代使用的邀请码');

        return Pms::service('PmsInviteCodeService')
            ->run('pmsOperatorInviteCode');
    }

    /**
     * @desc //六个车车巢，车巢升级PMS生成外部的邀请码 NEW
     */
    public function pmsCarNestInviteCode(Request $request)
    {
        Log::info('六个车车巢升级区代使用的邀请码');

        return Pms::service('PmsInviteCodeService')
            ->run('pmsCarNestInviteCode');
    }
}