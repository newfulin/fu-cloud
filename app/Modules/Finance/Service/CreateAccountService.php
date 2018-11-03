<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:40
 */
namespace App\Modules\Finance\Service ;


use App\Common\Contracts\Service;
use APP\Common\Contracts\ServiceRequest;
use App\Common\Criteria\Criteria;
use App\Common\Models\AcctAccountBalance;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Finance\Middleware\CheckAccountMiddle;
use App\Modules\Finance\Middleware\CreateAssetMiddle;
use App\Modules\Finance\Middleware\CreateCreditMiddle;
use App\Modules\Finance\Middleware\CreateFreezeMiddle;
use App\Modules\Finance\Middleware\CreateLendMiddle;
use App\Modules\Finance\Middleware\CreatePointsMiddle;
use App\Modules\Finance\Middleware\CreateRewardMiddle;
use App\Modules\Finance\Repository\AccountRepository;


class CreateAccountService extends Service {

    public $repo;
    public $user;

    public function __construct(AccountRepository $repo,CommUserRepo $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }


    public $middleware = [
        //only 仅action有效
        //except,除外action有效
//        ServiceMiddleware::class => [
//            'only' =>['createUser']
//        ]
        CheckAccountMiddle::class => [
            'only' => ['createAccount']
        ],
        CreateAssetMiddle::class => [
            'only' => ['createAccount']
        ],
        CreateCreditMiddle::class => [
            'only' => ['createAccount']
        ],
        CreateFreezeMiddle::class => [
            'only' => ['createAccount']
        ],
        CreateLendMiddle::class => [
            'only' => ['createAccount']
        ],
        CreatePointsMiddle::class => [
            'only' => ['createAccount']
        ],
        CreateRewardMiddle::class => [
            'only' => ['createAccount']
        ],
    ];

    public function getRules()
    {
        return [
            'createAccount' =>[
                'acct_id' => 'required',
                'user_id' => 'required'
            ]
        ];
    }

    /**
     * 根据用户编号user_id，创建用户初始账户,账户余额为：0
     * @desc 10:存款账户<BR> 20:信用账户<BR> 30:冻结账户<BR>60:红包账户
     * @return string code 响应码:成功时返回0000,失败时返回K9999
     */
    public function createAccount($request)
    {
        return $request;
    }
}