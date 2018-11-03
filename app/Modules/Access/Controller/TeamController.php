<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 17:43
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\CommUserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    public function getRules()
    {
        return [
            'getMyPromotion' => [
                'page'      => 'required',
                'pageSize'  => 'required'
            ],
            'getTeamListUser' => [
                'level_name' => 'required',
                'page'      => 'required',
                'pageSize'  => 'required'
            ],
            'getSuperiorRecommendAll' => [
                'user_id' =>'required'
            ],
            'getABCRecommendAll' => [
                'user_id' =>'required'
            ],
            'directSwitchTeamRelations' => [
                'user_id' => 'required|desc:用户ID',
                'recommend_id' => 'required|desc:新推荐用户ID',
            ]
        ];
    }

    /**
     * @desc 查询我的推广
     */
    public function getMyPromotion(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("查询我的推广:|" . $user_id);
        $param = [
            'user_id' => $user_id,
            'page' => $request->input('page'),
            'pageSize' => $request->input('pageSize'),
        ];

        return app('nxp-team')->query()
            ->getMyPromotionById($param);
    }

    /**
     * @desc 查询我推广的人数
     */
    public function getMyPromotionCount(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("查询我的推广人数:|" . $user_id);
        $param = [
            'user_id' => $user_id
        ];

        return app('nxp-team')->query()
            ->getMyPromotionCount($param);
    }


    /**
     * @desc 我的团队等级列表
     */
    public function getListLevel(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info('我的团队 | '.$user_id);
        $param = [
            'user_id' => $user_id,
        ];
        return app('nxp-team')->query()
            ->getListLevel($param);
    }

    /**
     * @desc 团队用户,XX等级的直推用户
     */
    public function getTeamListUser(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("团队用户XX等级的直推用户:|" . $user_id);

        $param = [
            'user_id' => $user_id,
            'level_name' => $request->input('level_name'),
            'page' => $request->input('page'),
            'pageSize' => $request->input('pageSize'),
        ];
        return app('nxp-team')->query()
            ->getTeamListUser($param);
    }

    /**
     * @desc 判断用户团队关系
     */
    public function judgeRecommendRelition(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('判断用户推荐关系| '.$user_id);

        $param = [
            'user_id' => $user_id
        ];
        $team = app('nxp-team')->query()
            ->getSuperiorRecommendAll($param);

        foreach ($team as $key => $val){
            if($val['user_id'] != '0'){
                $parent1 = app()->make(CommUserRepo::class)->getUser($val['user_id']);
            }

            if($val['user_tariff_code'] == config('const_user.NEST_USER.code')){
                if($val['user_id'] == '0'){
                    $recommend = getConfigure('A0110','P1201');
                    $unrecommend = getConfigure('A0120','P1201');
                    $arr['recommend'] = $recommend['property2'];
                    $arr['unrecommend'] = $unrecommend['property2'];
                    $arr['relation'] = 0;
                }else{
                    $arr['relation'] = 1;
                    $arr['user_name'] = $parent1['user_name'];
                }
            }
        }
        return $arr;
    }

    /**
     * @desc 获取用户的所有上级推荐用户
     * @param string user_id 用户ID
     * @param string level_name 用户等级
     * 返回所有数据，按照更新时间倒叙
     * @param string user_id 用户ID
     */
    public function getSuperiorRecommendAll(Request $request){
        $param = [
            'user_id' => $request->input('user_id')
        ];
        return app('nxp-team')->query()
            ->getSuperiorRecommendAll($param);
    }

    /**
    * @desc 查询我的直推上级用户,无推荐关系查询为空
    */
    public function getSuperiorParent1(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info('查询我的直推上级用户,无推荐关系查询为空| '.$user_id);

        return Access::service('TeamService')
            ->with('user_id',$user_id)
            ->run('getSuperiorParent1');
    }

    /**
     * @desc 查询推荐上三级
     */
    public function getABCRecommendAll(Request $request){
        return Access::service('TeamService')
            ->with('user_id',$request->input('user_id'))
            ->run('getABCRecommendAll');
    }

    /**
     * @desc 团队直接切换
     */
    public function directSwitchTeamRelations(Request $request){
        return Access::service('TeamService')
            ->with('user_id',$request->input('user_id'))
            ->with('recommend_id',$request->input('recommend_id'))
            ->run('directSwitchTeamRelations');
    }


}