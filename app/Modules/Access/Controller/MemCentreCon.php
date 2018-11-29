<?php
namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MemCentreCon extends Controller {

    public function getRules()
    {
        return [
            'memCentre' => [
            ],

        ];
    }

    /**
     * @desc 获取用户信息
     */
    public function memCentre(Request $request){
        $userId = $request->user()->claims->getId();
        Log::info('会员中心信息获取: '.$userId);
        return Access::service('MemCentreSer')
            ->with('userId',$userId)
            ->run('memCentre');
    }
}
