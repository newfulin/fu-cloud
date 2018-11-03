<?php
namespace App\Modules\Finance\Cashier;

use Illuminate\Support\Facades\Log;
use App\Modules\Finance\Repository\CommUserInfoRepository;
/**
 * 订单支付分润
 */

 class K0600 extends K0000 {

    /**
     * 分润比例设定
     */
    public $shareProfit = array(
        'direct'    => 2,//直推分润,
        'indirect'  => 2,//间接分润,
        'top'       => 2,//顶级分润,

        'P1201'     => 12,//VIP
        'P1301'     => 15, //总代理
        'P1311'     => 18, //合伙人
        'P1401'     => 21,//区代
        'P1501'     => 21,//市代
        'P1601'     => 21, //省代

        'P2101'     => 0, //招商经理
        'P2201'     => 0, //销售经理
        'P2301'     => 0, //市场总监
    );

//        'seniorExecutive'=> 1, //高管    ----  18800001001
//        'technology'     => 1, //技术部   ----  18800001101
//        'finance'        => 1, //财务部   ----  18800001201
//        'marketAdmin'    => 1, //讲师市场管理员  -- 18800001301
//        'generalOffice'  => 1  //总经办  --     18800001401

    public $repository;
    public $goReps;
    public $code = "K0600";

    /**
     * 注入Repository
     */
    public function  __construct(CommUserInfoRepository $Repository ){
         $this->repository = $Repository;
         //$this->getShareProfit($this->code);
    }


    
    /**
     * 
     */
    public function handle($markBookingOrder,$request)
    {
        Log::debug("Cashier::K0600.handle...订单支付分润");
        $BookingOrder = [];
        //$order = $request['order'];
        $goodOrder= $request['goodOrder'];
        Log::debug(json_encode($goodOrder));
        $transAmount = $goodOrder['promote_profit'];//可分润金额
        Log::debug("可分润金额:".$transAmount);
        $userinfo = $request['userinfo'];
        $userId = $userinfo['user_id'];
        $user_tariff_code = $userinfo['user_tariff_code'];
        //$codeLevel =$this->getCodeLevel($user_tariff_code);

        $teamRelation = $this->repository->getTeamRelation($userId);
        Log::debug(json_encode($teamRelation));

        //直推用户
        $parent1UserInfo = $this->repository->getEntity($teamRelation['parent1']);
        if($this->compareCode($parent1UserInfo['user_tariff_code'],$user_tariff_code)){
            $param =[];
            $param['credit_amount'] = Money()->getRate($transAmount,$this->shareProfit['direct']);
            $param['remark'] = '购物推广广告费';
            $param['process_id'] = $teamRelation['parent1']; //直推用户
            $param['batch_detail_id'] = '3.1';
            $BookingOrder['3.1'] = $this->getBranchBookingOrder($markBookingOrder,$param);
            Log::info("直推用户:".json_encode($param));
        }

        //间推用户
        $parent2UserInfo = $this->repository->getEntity($teamRelation['parent2']);
        if($this->compareCode($parent2UserInfo['user_tariff_code'],$user_tariff_code)){
            $param =[];
            $param['credit_amount'] = Money()->getRate($transAmount,$this->shareProfit['indirect']);
            $param['remark'] = '购物推广广告费';
            $param['process_id'] = $teamRelation['parent2']; //间推用户
            $param['batch_detail_id'] = '3.2';
            $BookingOrder['3.2'] = $this->getBranchBookingOrder($markBookingOrder,$param);
            Log::info("间推用户:".json_encode($param));
        }

        //顶推用户
        $parent3UserInfo = $this->repository->getEntity($teamRelation['parent3']);
        Log::info($parent3UserInfo['user_tariff_code']);
        if($this->compareCode($parent3UserInfo['user_tariff_code'],$user_tariff_code)){
            $param =[];
            $param['credit_amount'] = Money()->getRate($transAmount,$this->shareProfit['top']);
            $param['remark'] = '购物推广广告费';
            $param['process_id'] = $teamRelation['parent3']; //间推用户
            $param['batch_detail_id'] = '3.3';
            $BookingOrder['3.3'] = $this->getBranchBookingOrder($markBookingOrder,$param);
            Log::info("顶推用户|".json_encode($param));
        }

        //------------------------------级差收益---------------------------------------
        $rangePercentage = 0.00;//分出比例
        $UserInfoDepthList = $this->repository->getUserByTeam($userId);
        $UserInfo['depth'] = 0;
        $UserInfo['rangePercentage'] = $rangePercentage;
        $UserInfo = $this->getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder);
        if($UserInfo&&isset($UserInfo['BookingOrder'])){
            if(isset($UserInfo['BookingOrder']))
                $BookingOrder['3.4.1'] = $UserInfo['BookingOrder'];
            $UserInfo = $this->getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder);
        }
        if($UserInfo){
            if(isset($UserInfo['BookingOrder']))
                $BookingOrder['3.4.2'] = $UserInfo['BookingOrder'];
            $UserInfo = $this->getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder);
        }
        if($UserInfo){
            if(isset($UserInfo['BookingOrder']))
                $BookingOrder['3.4.3'] = $UserInfo['BookingOrder'];
            $UserInfo = $this->getDepthBookingOrder($UserInfoDepthList,$UserInfo,$transAmount,$markBookingOrder);
        }
        if($UserInfo&&isset($UserInfo['BookingOrder'])){
            $BookingOrder['3.4.4'] = $UserInfo['BookingOrder'];
        }

        //------------------------------区域买断流水---------------------------------------

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
        $rangePercentage = $UserInfo['rangePercentage'];//已分润系数
        $UserInfo = $this->getNextLevelUserInfo($UserInfoDepthList,'P1201',$depth);//级差收益
        Log::info("级差深度分润明细.3.4.".($depth+1)."级差收益:".json_encode($UserInfo));
        if($UserInfo){
            $param =[];
            //当前用户级差分润为我的分润系数减去已分润系数.,如果大于0,当前用户能享受分润
            $creditPercentage = Money()->calc($this->shareProfit[$UserInfo['user_tariff_code']],"-",$rangePercentage);//当前用户分润系数
            if($creditPercentage>0){
                $credit_amount = Money()->getRate($transAmount,$creditPercentage);
                $param['credit_amount'] = $credit_amount;
                $param['percentage'] = $creditPercentage;
                $param['remark'] = '购物推广管理费';
                $param['process_id'] = $UserInfo['user_id'];
                $param['batch_detail_id'] = '3.4.'.$depth;
                $UserInfo['rangePercentage'] = Money()->calc($rangePercentage,"+",$creditPercentage);
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