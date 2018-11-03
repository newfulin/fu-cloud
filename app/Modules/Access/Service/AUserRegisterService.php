<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 9:24
 */

namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Events\UserRegistAfterEvent;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\TeamRelationRepo;
use App\Modules\Finance\Finance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AUserRegisterService extends Service
{
    public $user;
    public $team;
    public function __construct(CommUserRepo $user,TeamRelationRepo $team)
    {
        $this->user = $user;
        $this->team = $team;
    }

    public function getRules(){

    }

    public $afterEvent = [
//        UserRegistAfterEvent::class
    ];

    public function handle($request)
    {
        Log::info('用户注册 -> ' .$request['mobile']);
        $id = ID();
        $request['user_id'] = $id;

        try {
            $this->createUser($request);
            $this->createTeam($request);
            $this->createAccount($request);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('数据插入失败', 9999);
        }

        return $request;
    }

    public function createUser($request){
        $data = [
            'id'               => $request['user_id'],
            'user_id'          => $request['user_id'],
            'user_name'        => $request['loginName'],
            'user_type'        => 10,
            'login_name'       => $request['mobile'],
            'agent_id'         => config('const_user.FORMAL_AGENT.code'),
            'user_tariff_code' => $request['level'],
            'register_type'    => '01',
            'level_name'       => $request['level'],
            'status'           => config('const_user.SIGN_UP.code'),
            'last_login_time'  => date('Y-m-d H:i:s'),
            'create_time'      => date('Y-m-d H:i:s'),
            'create_by'        => 'system',
            'update_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'system',
            'unionid'          => '',
            'pass_word'        => md5('123456'),
        ];
        //添加数据库
        $this->user->insert($data);
    }

    public function createTeam($request){
        $data = array(
            'id'               => ID(),
            'user_id'          => $request['user_id'],
            'user_name'        => $request['loginName'],
            'model_count'      => 3,
            'status'           => 1,
            'create_by'        => 'system',
            'create_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'system',
            'update_time'      => date('Y-m-d H:i:s'),
            'parent1'          => $request['user_id']
        );
        if($request['recommendId']){
            $teamInfo = $this->team->getRelation($request['recommendId']);

            for($i = 1;$i < 3;$i++){
                $data['parent'.($i+1)] = $teamInfo['parent'.$i];
            }
        }else{
            $data['parent2'] = $request['user_id'];
            $data['parent3'] = $request['user_id'];
        }

        $this->team->insert($data);
        $this->team->updateRecommendRela($request['user_id'],$request['recommendId']);
    }

    public function createAccount($request){
        Finance::service('CreateAccountService')
            ->with('acct_id',1)
            ->with('user_id',$request['user_id'])
            ->run('createAccount');
    }
}