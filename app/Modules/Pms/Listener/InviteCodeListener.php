<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 9:52
 */

namespace App\Modules\Pms\Listener;


use App\Modules\Access\Repository\CommCodeMasterRepo;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\InviteCodeRepo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Monolog\Formatter\LogglyFormatter;

class InviteCodeListener implements ShouldQueue
{
    public function __construct(InviteCodeRepo $code,CommCodeMasterRepo $master,CommUserRepo $user)
    {
        $this->code = $code;
        $this->master = $master;
        $this->user = $user;
    }

    public function handle($event){
        $request = $event->request;
        //获取用户信息
        $userInfo = $this->user->getUser($request['user_id']);
        //获取生成邀请码配置参数
        $master = $this->master->getConfigure('invite_code',$userInfo['user_tariff_code']);

        //升级金额
        //VIP
        $vipUser = $this->master->getConfigure('upgrade',config('const_user.VIP_USER.code'));
        //总代理商
        $agentUser = $this->master->getConfigure('upgrade',config('const_user.AGENT_USER.code'));
        //合伙人
        $totalAgentUser = $this->master->getConfigure('upgrade',config('const_user.CITYAGENT_USER.code'));

        Log::info($master['property1']);
        //所产生的(VIP(属性2)/总代理(属性3)/合伙人(属性4))邀请码个数 P1201->VIP P1301->总代理 P1311->合伙人

        //VIP
        $vip = $this->createCode($master['property2'],$vipUser['property2'],'VIP_USER',$request['user_id']);
        //总代理
        $agent = $this->createCode($master['property3'],$agentUser['property2'],'AGENT_USER',$request['user_id']);
        //合伙人
        $partner = $this->createCode($master['property4'],$totalAgentUser['property2'],'CITYAGENT_USER',$request['user_id']);

        $arr = array_merge($vip,$agent,$partner);
        Log::info('------------'.json_encode($arr));
        return $this->code->insert($arr);
    }

    /**
     * @param $number int 邀请码数量
     * @param $amount int 邀请码价值
     * @param $config string 等级配置
     * @param $user_id string 用户ID
     * @return array
     */
    public function createCode($number,$amount,$config,$user_id){

        $data = [
            'user_id' => $user_id,
            'old_user_id' => $user_id,
            'state' => '10',
            'create_time' => date('Y-m-d H:i:s'),
            'create_by' => $user_id,
            'update_time' => date('Y-m-d H:i:s'),
            'update_by' => $user_id
        ];
        $arr = [];
        for ($i = 0; $i < $number;$i++){
            $data['id'] = ID();
            $data['code'] = strtoupper(MD5(ID()));
            $data['amount'] = $amount;
            $data['level_name'] = config('const_user.'.$config.'.code');
            $arr[] = $data;
        }
        return $arr;
    }
}