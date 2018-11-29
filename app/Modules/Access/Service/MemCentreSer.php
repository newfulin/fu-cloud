<?php
namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Transaction\Repository\CommUserInfoRepository;

class MemCentreSer extends Service
{
    public function getRules()
    {

    }
    protected $user;
    public function __construct(CommUserInfoRepository $user)
    {
        $this->user = $user;
    }
    public function memCentre($request)
    {
        $ret = $this->user->memCentre($request['userId']);
        $ret['levelIMG'] = config('const_user.'.$ret['user_tariff_code'].'.code');
        return $ret;
    }
}