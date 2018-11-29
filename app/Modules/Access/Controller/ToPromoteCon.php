<?php

namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ToPromoteCon extends Controller {

    public function getRules()
    {
        return [
            'getShareInfo' => [
            ],

        ];
    }

    /**
     * @desc 微信分享信息
     * @param Request $request
     * @return mixed
     */
    public function wxShareInfo(Request $request){
        return Access::service('ToPromoteSer')
            ->run('wxShareInfo');
    }
}
