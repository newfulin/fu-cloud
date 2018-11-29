<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/30
 * Time: 10:55
 */

namespace App\Modules\Headline\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Headline\Headline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShareController extends Controller
{
    public function getRules()
    {
        return [
            'getTopShare' => [
                'id' => 'required',
                'user_id' => ''
            ],
            'webShare' => [
                'url' => 'required'
            ]
        ];
    }

    /**
     *@desc 头条分享
     */
    public function getTopShare(Request $request)
    {
      $request->setTrustedProxies(array('172.16.50.22'));
        $ip = $request->getClientIp();

        Log::debug('$ip__________________________'.$ip);
        return Headline::service('ShareService')
            ->with('id',$request->input('id'))
            ->with('ip',$ip)
            ->with('user_id',$request->input('user_id'))
            ->with('kind_of','05')
            ->run('getTopShare');
    }

    /**
     * @desc 获取Web端页面二次分享的信息
     * @param Request $request
     * @param  mixed
     */
    public function webShare(Request $request)
    {
        return Headline::service('ShareService')
            ->with('url',$request->input('url'))
            ->run('webShare');
    }

}