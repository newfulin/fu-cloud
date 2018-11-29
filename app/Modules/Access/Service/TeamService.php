<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 15:55
 */

namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Events\SwitchTeamRelationsAfterEvent;
use App\Modules\Access\Repository\CommUserRepo;

class TeamService extends Service {
	public function getRules() {
		// TODO: Implement getRules() method.
	}

	public $afterEvent = [
		SwitchTeamRelationsAfterEvent::class => [
			'only' => ['switchTeamRelations','directSwitchTeamRelations'],
		],
	];

	//判断用户团队关系
	public function judgeRecommendRelition($request) {

		$ret = app('nxp-team')->query()
			->judgeRecommendRelition($request);

		$recommend = config('const_user.RECOMMEND');

		if (isset($ret->login_name) && $ret->login_name == $recommend) {
			return 0;
		}
		return 1;
	}

	//团队使用邀请码切换
	public function switchTeamRelations($request) {

		$userInfo = app()->make(CommUserRepo::class)->getReferralCode($request);
		if (!$userInfo) {
			Err('请输入正确的推荐码!');
		}

		if ($userInfo['user_tariff_code'] == config('const_user.NEST_USER.code')) {
			$request['recommend_id'] = $userInfo['id'];
			return $request;
		}

		$param = [
			'user_id' => $userInfo['id'],
		];
		$team = app('nxp-team')->query()
			->getSuperiorRecommendAll($param);
		foreach ($team as $key => $val) {
			if ($val['user_tariff_code'] == config('const_user.NEST_USER.code')) {
				$request['recommend_id'] = $userInfo['id'];
				return $request;
			} else {
				Err('请输入正确的推荐码!');
			}
		}
	}

	public function getSuperiorParent1($request) {
		$arr['relation'] = 0;
		$ret = app('nxp-team')->query()
			->judgeRecommendRelition($request);

		$recommend = config('const_user.RECOMMEND');

		if (isset($ret->login_name) && $ret->login_name == $recommend) {
			return $arr;
		}

		$param = [
			'user_id' => $ret->user_id,
		];
		$ret = app()->make(CommUserRepo::class)->getUser($param);
		if ($ret) {
			$arr['relation'] = 1;
			$arr['user_name'] = $ret['user_name'];
			return $arr;
		} else {
			return $arr;
		}
	}

	public function getABCRecommendAll(CommUserRepo $user,$request){
        $param = [
            'user_id' => $request['user_id']
        ];
        $ret = app('nxp-team')->query()
            ->getABCRecommendAll($param);
        $arr['parent1'] = $user->getUserById($ret['parent1']);
        $arr['parent2'] = $user->getUserById($ret['parent2']);
        $arr['parent3'] = $user->getUserById($ret['parent3']);
        return $arr;
    }

    //团队关系直接切换
    public function directSwitchTeamRelations(CommUserRepo $user,$request){
        //检查用户是否存在
        $userInfo = $user->getUser($request['user_id']);
        if(!$userInfo) Err('该用户不存在');
        //检查推荐用户是否存在
        $recommendInfo = $user->getUser($request['recommend_id']);
        if(!$recommendInfo) Err('推荐用户不存在');
        return $request;
    }
}