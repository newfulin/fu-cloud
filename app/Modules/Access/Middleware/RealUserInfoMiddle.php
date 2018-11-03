<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/2
 * Time: 15:21
 */
namespace App\Modules\Access\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\CommUserRepo;
use Closure;

class RealUserInfoMiddle extends Middleware{

    public $repo;
    public function __construct(CommUserRepo $repo)
    {
        $this->repo = $repo;
    }
    public function handle($request, Closure $next)
    {
        $data = [
//            'user_name'            => $request['userName'],
            'crp_nm'               => $request['userName'],
            'crp_id_type'          => $request['crpIdType'],
            'crp_id_no'            => $request['idNo'],
            'account_name'         => $request['accountName'],
            'account_no'           => $request['accountNo'],
            'bank_reserved_mobile' => $request['bankLeaveMobile'],
            'regist_address'       => $request['provinceName'].$request['cityName'],
            'open_bank_name'       => $request['openBankName'],
            'bank_line_name'       => $request['bankLineName'],
            'bank_code'            => $request['bankCode'],
            'status'               => config('const_user.OFFICIALLY.code'),
        ];
        if(!empty($request['login_name'])){
            $data['login_name'] = $request['login_name'];
            $data['pass_word'] = md5($request['pass_word']);
        }

//        $request['data'] = $this->repo->where('user_id',$request['user_id'])->update($data);
        $request['data'] = $this->repo->updateUser($request['user_id'],$data);
        return $next($request);
    }

}