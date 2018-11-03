<?php
/**
 * 红包类
 */
namespace App\Modules\Finance\Service ;

use App\Common\Contracts\Service;
use Illuminate\Support\Facades\Log;
use App\Modules\Finance\Repository\RedPacketRepository;
use App\Modules\Finance\Repository\CommUserInfoRepository;

/**
 * 红包类
 * *
 */
class HongbaoService extends Service{

    public $repository;
    public $userRepository;

    /**
     * 注入Repository
     */
    public function  __construct(RedPacketRepository $Repository,CommUserInfoRepository $userRepository){
         $this->repository = $Repository;
         $this->userRepository = $userRepository;
    }

    /**
     * @return mixed
     */
    public function getRules()
    {
        return [
            'handle' =>[
                'userId'   =>'required'
            ]
        ];
    }


   /**
     * 执行
     */
    public function handle($request)
    {
        Log::info("创建VIP用户红包.Start-->>");
        $userId = $request['userId'];
        $ret = $this->userRepository->getEntity($userId);
        //Log::info($ret);
        if(!$ret){
            Log::error("1999:用户信息不存在");
            Err("用户信息不存在:1999");
        }
        $vipPacketManage = $this->repository->getPacketManage('VIP红包');
        //Log::debug(json_encode($vipPacketManage));
        $packet_name =  $vipPacketManage['packet_name'];
        $status =  $vipPacketManage['status'];
        $packet_amount =  $vipPacketManage['packet_amount'];//红包金额
        $granting_amount =  $vipPacketManage['granting_amount'];//红包数量
        $hbData = [];
        //Log::debug($status);
        $acount = $this->checkHongbao($userId,$packet_amount);
        if($acount>0){
            return '0000';
        }
        if($status=="0"){
            $num = 1; 
            while($num <= $granting_amount) {
                $hbData['id'] = ID();
                $hbData['packet_name'] = 'VIP红包';
                $hbData['packet_amount'] = $packet_amount;
                $hbData['granting_object'] = $userId;
                $hbData['desr'] = '红包';
                $hbData['status'] = '01';
                $hbData['create_time']= date("Y-m-d H:i:s");
                $hbData['create_by']= 'system';
                $hbData['update_time']= date("Y-m-d H:i:s");
                $hbData['update_by']= 'system';
                //Log::debug(json_encode($hbData));
                $this->repository->save($hbData);
                $num++;
            } 
        }else{
            Log::error("4999:红包未开启");
            Err("红包未开启:4999");
        }
        Log::info("创建VIP用户红包.End::".$granting_amount);
        return '0000';
    }

    /**
     * 红包检测
     */
    protected function checkHongbao($userId,$packetAmount){
        $acount = $this->repository->getHBCountByUserId($userId,$packetAmount);
        if($acount>0){
            Log::info($userId." :: 用户VIP红包,已存在:: ".$acount);
        }
        return $acount;
    }
    
    /**
     * *******************************************************
     */

}