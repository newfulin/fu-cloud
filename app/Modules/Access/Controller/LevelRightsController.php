<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 14:09
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LevelRightsController extends Controller
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc 获取等级权益
     */
    public function getLevelRights(Request $request){
        Log::info('获取等级权益');
        return Access::service('LevelRightsService')
            ->run('getLevelRightsList');
    }
}