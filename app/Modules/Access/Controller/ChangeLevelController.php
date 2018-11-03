<?php

namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChangeLevelController extends Controller {

    public function getRules()
    {
        return [
            'changeLevel' => [
                'level' => 'required',
            ],

        ];
    }

    /**
     * @desc 更新用户等级
     * @param Request $request
     * @return mixed
     */
    public function changeLevel(Request $request){
        return Access::service('changeLevelService')
            ->with('level',$request->input('level'))
            ->run('changeLevel');
    }
}
