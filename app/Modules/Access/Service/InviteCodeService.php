<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 10:11
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Common\Models\InviteCode;
use App\Modules\Access\Repository\CommCodeMasterRepo;
use App\Modules\Access\Repository\CommPushTempletRepo;
use App\Modules\Access\Repository\InviteCodeRepo;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\SixCarCommUserInfoRepo;
use App\Modules\Access\Repository\TeamRelationRepo;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use App\Modules\Transaction\Trans;
use Illuminate\Support\Facades\Log;

class InviteCodeService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $user;
    public $code;
    public function __construct(CommUserRepo $user, InviteCodeRepo $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    //使用邀请码
    public function useCode(TranTransOrderRepo $order,$request)
    {
        $orderInfo = $order->getTransOrderByUserId($request['code'],$request['userId']);
        if($orderInfo) Err('不可重复提交');

        //通过用户id获取用户信息
        $user = $this->user->getUser($request['userId']);
        //邀请码权限，是否属于该用户

        $level = $user['user_tariff_code'];
        Log::info('$level------使用人的等级---------------'.$level);
        if($level != 'P1101' && $level != 'P1201' && $level != 'P1111' && $level != 'P1301' ){
            Err('很抱歉，你的等级过高，不能使用激活码升级');
        }
        switch($level){
            case "P1101":
                $oldLevel = 1;
                break;
            case "P1111":
                $oldLevel = 2;
                break;
            case "P1201":
                $oldLevel = 3;
                break;
            case "P1301":
                $oldLevel = 4;
                break;
        }

        //通过邀请码获取邀请码的信息
        $inviteCodeInfo = $this->code->getInfoByCode($request['code']);
        if(!$inviteCodeInfo){
            Err('邀请码错误，请核对后重新输入！！！');
        }

        if(time() > strtotime($inviteCodeInfo['effective_time'])){
            Err('邀请码已过期!');
        }

        switch($inviteCodeInfo['level_name']){
            case 'P1201':
                $newLevel = 3;
                break;
            case 'P1301':
                $newLevel = 4;
                break;
            case 'P1311':
                $newLevel = 5;
                break;
            default:
                Err("邀请码等级过高");
                break;
        }
        Log::info('$oldLevel——————'.$oldLevel);
        Log::info('$newLevel________'.$newLevel);
        //使用用户的等级要小于邀请码的等级
        if($oldLevel >= $newLevel){
            Err('很抱歉，你的等级过高，大于或等于邀请码的等级');
        }


        //判断邀请码是否已经使用
        $status = $inviteCodeInfo['state'];
        if($status == '20'){
            Err('邀请码已经使用或邀请码错误，请重新输入');
        }

        //请求财务 'A0130' => ['msg' => 'VIP邀请码升级'],
        //        'A0131' => ['msg' => '总代理邀请码升级'],
        //        'A0132' => ['msg' => '合伙人邀请码升级'],

        $ret = Trans::service('ChannelTrans')
            ->with('trans_amt'    , $inviteCodeInfo['amount'])
            ->with('tariff_code'  , $user['user_tariff_code'])
            ->with('user_id'      , $user['user_id'])
            ->with('invite_code'  , $request['code'])
            ->with('type'         , '10') //10邀请码升级  20缴费升级
            ->with('time'         , time());
        switch ($inviteCodeInfo['level_name']){
            case 'P1201':
                $ret->with('business_code','A0130');
                break;
            case 'P1301':
                $ret->with('business_code','A0131');
                break;
            case 'P1311':
                $ret->with('business_code','A0132');
                break;
        }
      $data =   $ret->run('customChannel');

        if($data['code'] == '0000'){
            Err('使用成功，待审核');
        }
    }

    //邀请码转赠
    public function giveInviteCode($request)
    {
        $code = $request['code'];
        $userId = $request['userId'];
        $receive = $this->user->getUserByLoginName($request['tel']);

        //检测接收人是否存在
        if(!$receive){
            Err('输入的手机号有误，请重新输入！！！');
        }

        $receiveId = $receive['user_id'];
        $time = time();
        $dateTime = date('Y-m-d H:i:s',$time);

        //被转移人身份,必须为VIP.
        $level = $receive['user_tariff_code'];
        Log::info('$level-----被转移人的等级---------------'.$level);

        if($level < config('const_user.VIP_USER.code')){
            Err('很抱歉，被转移人的等级须为VIP用户!');
        }

        //检测该邀请码是否转赠过，最多只能转赠一次
        $inviteCodeInfo = $this->code->getInfoByCode($request['code']);
        if($inviteCodeInfo['old_user_id'] != $inviteCodeInfo['user_id']){
            Err('该激活码无法转赠,最多只能转赠一次');
        }

        //检测邀请码是否是该用户未使用的状态
        if ($inviteCodeInfo['state'] == '20')
        {
            Err('该激活码已经使用，无法转赠');
        }

        // 进行邀请码转赠
        $params = array(
            'user_id' => $receiveId,
            'change_time' => $dateTime,
            'change_state' => '20',
        );
        $re = $this->code->giveInviteCode($code,$params);
        return $re;


    }

    //获取邀请码页面P1201：VIP P1301：总代理 P1201：合伙人
    public function giveInviteList(InviteCodeRepo $code, $request)
    {
        //通过用户id获取邀请码列表
        $ret = $code->getCodeInfoByUserId($request);

        return $ret['data'];
    }

    //获取原六个车用户信息
    public function getOldUserInfo(SixCarCommUserInfoRepo $oldUser, $request)
    {
        $useInfo = $oldUser->getUser($request['userId']);
        $ret = [
            'invite_code' => $request['invite_code'],
            'tel' => $useInfo['login_name'],
            'level' => $useInfo['user_tariff_code']
        ];
        return $ret;
    }

    //原六个车用户邀请码升级  六个车合伙人  合作商|车巢|代理商
    public function oldUserUpgrade(CommUserRepo $user, TeamRelationRepo $team, InviteCodeRepo $code,TranTransOrderRepo $order, $request)
    {
        //判断活动时间段
        $this->judgeTimePeriod();
        //判断该用户是否存在
        $userInfo = $user->getUserByLoginName($request['tel']);

        if(!$userInfo){
            Err('登陆商城实名认证后，重试！！！登陆手机号为六个车对应账户的手机号.');
        }

        //通过邀请码查信息
        $codeInfo = $code->getInfoByCode20($request['invite_code']);
        if(!$codeInfo){
            Err('邀请码错误，请核对后重新输入！！！');
        }
        //判断邀请码是否使用
        if ($codeInfo['state'] == '20')
        {
            Err('该激活码已经使用，无法升级');
        }
        //判断邀请码是否符合使用的等级
        $codeLevel = $codeInfo['level_name'];

        switch ($request['old_level']){
            case 'P1501':

                if($codeLevel != 'P1401'){
                    Err('邀请码等级不匹配,请使用区代升级的邀请码');
                }
                break;
            case 'P1221';
                if($codeLevel != 'P1311'){
                    Err('邀请码等级不匹配,请使用合伙人升级的邀请码');
                }
                break;
            case 'P1401':
            case 'P1301':
                if($codeLevel != 'P1301'){
                    Err('邀请码等级不匹配，请使用总代理升级的邀请码');
                }
                break;
        }

        // 检测该用户是否是他团队的
        $oldUserId = $codeInfo['old_user_id'];
//        $check = $team->checkMyRecommend($userInfo['user_id'],$oldUserId);
//        if (!$check) {
//            Err('此手机号不在邀请码提供者团队之中，请核实后重试。');
//        }
        if($userInfo['user_tariff_code'] > 'P1111'){
            Err('很抱歉你的等级过大，无法使用邀请码升级');
        }

        //判断该用户是否是会员P1111
        if($userInfo['user_tariff_code'] != 'P1111'){
            Err('请在商城购买一件商品升级为会员后，继续使用邀请码升级');
        }

        $ret = Trans::service('ChannelTrans')
            ->with('tariff_code'  , $userInfo['user_tariff_code'])
            ->with('user_id'      , $userInfo['user_id'])
            ->with('invite_code'  , $request['invite_code'])
            ->with('type'         , '20') //10邀请码升级  20缴费升级
            ->with('time'         , time());

        switch ($request['old_level']){
            case 'P1501':
                $orderInfo = $order->getTransOrderByUserIdType($userInfo['user_id'],'A1140');
                if($orderInfo) Err('不可重复提交');

                $ret->with('business_code','A1140')
                    ->with('trans_amt'    , 100000);
                break;
            case 'P1221':
                $orderInfo = $order->getTransOrderByUserIdType($userInfo['user_id'],'A2233');
                if($orderInfo) Err('不可重复提交');

                $ret->with('business_code','A2233')
                    ->with('trans_amt'    , 30000);
                break;
            case 'P1401':
            case 'P1301':
                $orderInfo = $order->getTransOrderByUserIdType($userInfo['user_id'],'A1233');
                if($orderInfo) Err('不可重复提交');

                $ret->with('business_code','A1233')
                    ->with('trans_amt'    , 10000);
                break;
        }
        return   $ret->run('customChannel');

    }

    //判断活动时间段
    public function judgeTimePeriod(){
        $time = strtotime("2018-11-10 23:59:59");
        if(time() > $time){
            Err('商城确权活动已结束!');
        }
        $current_time = strtotime(date("H:i"));
        $start = strtotime('09:00');
        $end = strtotime('12:00');
        if($current_time < $start || $current_time > $end){
            Err('限时开放商城确权活动，确权时间为每日9：00~12：00，截止日期：2018年11月10日!');
        }
    }

}