<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/2/28
 * Time: 17:10
 */
namespace App\Modules\Finance\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\AccountRepository;
use Closure;

class CreateLendMiddle extends Middleware{

    public $repo ;

    public function __construct(AccountRepository $repo)
    {

        $this->repo = $repo;
    }


    public function handle($request, Closure $next)
    {
        /**
         * @desc 创建红包账户
         */
        $this->repo->save($request['acct_id'],$request['user_id'],$request['mercObj'],$request['lend']);

        return $next($request);
    }


}