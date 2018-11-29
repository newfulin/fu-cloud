<?php
namespace App\Modules\Finance\Cashier;

use Illuminate\Support\Facades\Log;
use App\Modules\Finance\Repository\CommUserInfoRepository;
/**
 * VIP加盟PMS
 */

 class K0230 extends K0000 {

    /**
     * 分润比例设定 , 因为直推,间推,顶推 都是10% ,所以推荐奖励 按照  用户级别都是10  向上找找到谁10% 分润即可.
     */
    public $shareProfit = array(
        'direct'    => 10,//直推分润,
        'indirect'  => 10,//间接分润,
        'top'       => 10,//顶级分润,

        'P1201'     => 10,//VIP
        'P1301'     => 10, //总代理
        'P1311'     => 10, //合伙人
        'P1401'     => 10,//区代
        'P1501'     => 10,//市代
        'P1601'     => 10, //省代

        'system'    => 8, //平台里面的系统角色

        'P2101'     => 10, //招商经理
        'P2201'     => 10, //销售经理
        'P2301'     => 3, //市场总监
    );

    public $repository;
    public $code = "K0230";

    /**
     * 注入Repository
     */
    public function  __construct(CommUserInfoRepository $Repository){
         $this->repository = $Repository;
         //$this->getShareProfit($this->code);
    }


    
    /**
     * 
     */
    public function handle($markBookingOrder,$request)
    {
        Log::debug("Cashier::K0230.handle...VIP加盟");
        $BookingOrder = [];
        $transAmount = $request['transAmount'];
        $userinfo = $request['userinfo'];
        $userId = $userinfo['user_id'];
        $teamRelation = $this->repository->getTeamRelation($userId);
        Log::debug(json_encode($teamRelation));
        //------------------------------级差收益---------------------------------------
        $rangePercentage = 0.00;//分出比例
        $UserInfoDepthList = $this->repository->getUserByTeam($userId);
        $UserInfo['depth'] = 0;
        $UserInfo['rangePercentage'] = $rangePercentage;
        $UserInfo = $this->getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder);
        if($UserInfo&&isset($UserInfo['BookingOrder'])){
            if(isset($UserInfo['BookingOrder']))
                $BookingOrder['3.1.1'] = $UserInfo['BookingOrder'];
            $UserInfo = $this->getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder);
        }
        if($UserInfo){
            if(isset($UserInfo['BookingOrder']))
                $BookingOrder['3.1.2'] = $UserInfo['BookingOrder'];
            $UserInfo = $this->getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder);
        }
        if($UserInfo){
            if(isset($UserInfo['BookingOrder']))
                $BookingOrder['3.1.3'] = $UserInfo['BookingOrder'];
            //$UserInfo = $this->getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder);
        }
        Log::info("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //找到合伙人级别,限时奖励  所有推荐关系的 VIP 10% 奖励  需要单独处理下10%.
        $UserInfo = $this->getLevelUserInfo($UserInfoDepthList,'P1311');//合伙人
        if($UserInfo&&$UserInfo['user_id']>0){
            $creditPercentage = Money()->format($this->shareProfit[$UserInfo['user_tariff_code']]);
            $credit_amount = Money()->getRate($transAmount,$creditPercentage);
            $param['credit_amount'] = $credit_amount;
            $param['percentage'] = $creditPercentage;
            $param['remark'] = '合伙人VIP推广提成';
            $param['process_id'] = $UserInfo['user_id'];
            $param['batch_detail_id'] = '3.1.4';
            $UserInfo['rangePercentage'] = $creditPercentage;
            Log::info($param['remark']."::".json_encode($param));
            $UserInfo['BookingOrder']= $this->getBranchBookingOrder($markBookingOrder,$param);
            $BookingOrder['3.1.4'] = $UserInfo['BookingOrder'];//-------------------------------------------------
        }
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //省代给与8%的绩效提成
        $UserInfoP1601= $this->getLevelUserInfo($UserInfoDepthList,"P1601");
        if($UserInfoP1601['user_id']>0){
            //*********
            $param =[];
            $param['credit_amount'] = Money()->getRate($transAmount,$this->shareProfit['system']);
            $param['remark'] = '购物系统级分润';
            $param['process_id'] = $UserInfoP1601['user_id'];
            $param['batch_detail_id'] = '3.5';
            $BookingOrder['3.5'] = $this->getBranchBookingOrder($markBookingOrder,$param);
            Log::info("购物系统级分润:".json_encode($param));
        }
        //P2301 总监管理提成
        $UserInfoP2301= $this->getLevelUserInfo($UserInfoDepthList,"P2301");
        if($UserInfoP2301['user_id']>0){
            //*********
            $param =[];
            $param['credit_amount'] = Money()->getRate($transAmount,$this->shareProfit['P2301']);
            $param['remark'] = '总监管理提成';
            $param['process_id'] = $UserInfoP2301['user_id'];
            $param['batch_detail_id'] = '3.6';
            $BookingOrder['3.6'] = $this->getBranchBookingOrder($markBookingOrder,$param);
            Log::info("总监管理提成:".json_encode($param));
        }
        return $BookingOrder;

    }

     /**
      * 级差深度分润明细
      * @param $UserInfoDepthList
      * @param $UserInfo
      * @param $transAmount
      * @return null
      */
     protected function getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder){

         $depth = $UserInfo['depth'];
         $rangePercentage = $UserInfo['rangePercentage'];//分润系数
         $UserInfo = $this->getNextLevelUserInfo($UserInfoDepthList,'P1201',$depth);//级差收益
         Log::info("级差深度分润明细.3.1.".($depth+1)."级差收益:".json_encode($UserInfo));
         if($UserInfo){
             $param =[];
             //当前用户级差分润为我的分润系数减去已分润系数.,如果大于0,当前用户能享受分润
             $creditPercentage = Money()->format($this->shareProfit[$UserInfo['user_tariff_code']]);
             if($creditPercentage>0){
                 $credit_amount = Money()->getRate($transAmount,$creditPercentage);
                 $param['credit_amount'] = $credit_amount;
                 $param['percentage'] = $creditPercentage;
                 $param['remark'] = '招商加盟业绩提成';
                 $param['process_id'] = $UserInfo['user_id'];
                 $param['batch_detail_id'] = '3.1.'.$depth;
                 $UserInfo['rangePercentage'] = $creditPercentage;
                 Log::info($param['remark']."::".json_encode($param));
                 $UserInfo['BookingOrder']= $this->getBranchBookingOrder($markBookingOrder,$param);
             }else{
                 Log::debug("下级高于自己的级别 -无法分润-  级差收益:".$creditPercentage);
                 $UserInfo['rangePercentage'] = $rangePercentage;
                 Log::debug("未来这里要刺激用户升级!!");
             }

         }
         return $UserInfo;
     }

     /**
      * 获取分润参数,通过数据字典设置
      */
     protected function getShareProfit($code){

        foreach($this->shareProfit as $k => $v ){
            $ret = getConfigure($code,$k);
            //Log::debug(json_encode($ret));
            $this->shareProfit[$k]=$ret['property2'];
        }
        Log::debug(json_encode($this->shareProfit));
     }


 }