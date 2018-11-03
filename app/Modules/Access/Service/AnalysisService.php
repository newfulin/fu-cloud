<?php
/**
 * 阅读量统计
 * 队列异步接收,处理类库
 */

namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;

use App\Modules\Access\Repository\ShareControlRepo;
use App\Modules\Transaction\Repository\ActivityManageRepo;
use App\Modules\Transaction\Repository\CommUserInfoRepository;
use App\Modules\Transaction\Trans;
use Illuminate\Support\Facades\Log;

class AnalysisService extends Service
{

    public $repo;

    public function __construct(ShareControlRepo $repo)
    {
        $this->repo = $repo;
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function handle($request){
        $request['time'] = time();

        Log::info("分享统计(队列异步接收,处理类库)>>AnalysisService.统计监听".json_encode($request));
        // 分享确认(info 为 查看,不记录)
        if ($request['Remark'] == 'info') {
            Log::info("-----------app内查看分享,不统计");
            return '0000';
        }
        // 同用户分享的，同篇文章，同微信用户查看只累计一次----------------------活动用检测
        $checkOpenId = $this->checkOpenId($request);
        // 生成分享 分享ID
        $countId = $this->createShareCount($request);
        if ($countId == 'false')
        {
            Err('生成分享统计失败');
        }

        // 确认活动时间在有效期内
        $actInfo = $this->checkActivity($request['time']);
        if ($actInfo == 'false'){
            Log::info("-----------当前活动已过期");
            return '0000';
        }
        $checkNum = $this->checkNum($request);
        // 同用户每天同文章只统计一次 01 02 04 05
//        if ($checkNum != 'false') {
//            Log::info("-----------同用户每天同文章只统计一次 01 02 03 05");
//            return '0000';
//        }
        // 同用户分享的，同篇文章，同微信用户查看只累计一次----------------------活动用检测
        if ($checkOpenId != 'false') {
            return '0000';
        }

        // 用户等级检测
        $checkLevel = $this->checkLevel($request['UserId'],$actInfo['user_control']);
        if ($checkLevel != 'false'){
            Log::debug('用户等级符合要求----------------'.$checkLevel);
            // 参数整理
            $tariff_code = app()->make(CommUserInfoRepository::class)->getTariffCode($request['UserId']);
            // 请求财务记账分润
            return Trans::service('ActivityTrans')
                ->with('business_code', config('interface.CHANNEL.share'))
                ->with('trans_amt', $actInfo['share_each_price'])
                ->with('tariff_code', $tariff_code)
                ->with('user_id', $request['UserId'])
                ->with('detailId', $countId)
                ->with('time', $request['time'])
                ->with('summaryId',ID())
                ->run('cashBook');
        }

//        return $request;
        return '0000';

    }
    // 同用户分享的，同篇文章，同微信用户查看只累计一次
    public function checkOpenId($request)
    {
        $this->repo->checkOpenId($request['UserId'],$request['Id'],$request['time'],$request['OpenId']);
        $checkNum = app()->make(ShareControlRepo::class)->checkOpenId($request['UserId'],$request['Id'],$request['time'],$request['OpenId']);
        return $checkNum;
    }
    // 检测用户等级是否符合活动需求
    public function checkLevel($user_id,$limit)
    {
        $level = app()->make(CommUserInfoRepository::class)->getTariffCode($user_id);
        $limit = explode(',',$limit);
        foreach ($limit as $val) {
            if ($level == $val) {
                return $level;
            }
        }
        return 'false';
    }
    // 同用户每天同类型只统计一次 01 02 03 05
    public function checkNum($request)
    {
        // 查询用户在该类型今天是否已经分享（区分查看open_id）-----------------------------------------------------------------
        $checkNum = app()->make(ShareControlRepo::class)->checkNum($request['UserId'],$request['Id'],$request['time']);
        return $checkNum;

    }
    //　检测是否参与活动期限
    public function checkActivity($time)
    {
        $actId = config('const_share.Activity.actId');
        $actInfo = app()->make(ActivityManageRepo::class)->checkActInfo($actId);
        $start_data= strtotime($actInfo['start_data']);
        $end_data = strtotime($actInfo['end_data']);
        if ($time >= $start_data && $time <= $end_data && $actInfo['status'] == '01') {
            Log::info('活动进行中|actId='.$actId);
            $re = array(
                'share_each_price' => $actInfo['share_each_price'],
                'user_control' => $actInfo['user_control']
            );
            return $re;
        }
        return 'false';
    }

    public function createShareCount($request)
    {
        $data['id'] = ID();
        $data['ip'] = $request['IP'];
        $data['share_id'] = $request['Id'];
        $data['type'] = $request['Type'];
        $data['user_id'] = $request['UserId'];
        $data['title'] = $request['Name'];
        $data['desc'] = $request['Desc'];
        $data['open_id'] = $request['OpenId'];
        $data['create_time']= date("Y-m-d H:i:s",$request['time']);
        $data['create_by']= 'analysis';
        $data['update_time']= date("Y-m-d H:i:s",$request['time']);
        $data['update_by'] = 'analysis';
        $re = $this->repo->insert($data);
        if (!$re) {
            return 'false';
        }
        return $data['id'];
    }
    public function addStatistics($request)
    {
        switch ($request['Type'])
        {
            case '04':
                $count =  app()->make(ShelfProductRepo::class)->getOldCount($request['Id']);

                $count = $count->statistics +1;
                $ret =  app()->make(ShelfProductRepo::class)->updateCollection($request['Id'],$count);
                if($ret){
                    return '数据更新成功';
                }

                break;
            default:
                $amount =  app()->make(WeChatShareRepository::class)->getOldCount($request['Id']);
                $amount = $amount->statistics + 1;
                app()->make(WeChatShareRepository::class)->updateCollection($request['Id'],$amount);
                break;
        }
        return '0000';
    }

}