<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 08:53
 */
namespace App\Modules\Finance\Service ;


use App\Modules\Finance\Finance;
use App\Common\Contracts\Service;
use App\Modules\Finance\Repository\AcctBookingPolicyRepository;
use Illuminate\Support\Facades\Config;
use App\Modules\Finance\Middleware\Cashier\CheckLockFinance;
use App\Modules\Finance\Repository\AcctBookingTempletRepository;
use Illuminate\Support\Facades\Log;

/**
 * 收银入口,事后中间件应为记账策略中间件,
 * 取得真正的记账码,然后进行记账服务
 * Class CashierService
 * @package App\Modules\Finance\Service
 */
class CashierService extends Service {



    //收银统一的检查中间件...
    public $middleware = [
        CheckLockFinance::class
    ];

    public $repository;
    public $repoPolicy;

    /**
     * 注入Repository
     */
    public function  __construct(AcctBookingTempletRepository $Repository,AcctBookingPolicyRepository $repoPolicy){
         $this->repository = $Repository;
         $this->repoPolicy = $repoPolicy;
    }

    /**
     * 参数验证
     * @return array
     */
    public function getRules()
    {
        Log::debug("CashierService.getRules...");
        return [];
    }


    /**
     * 执行
     */
    public function handle($request)
    {
        //记账批次
        $request['book']['batch_id'] =ID();
        //策略处理
        // $request = Finacce::serveice('PolicyService')
        //         ->with($request)
        //         ->setMideleware(conig('finance.policy.K0110')))
        //         ->run();
        $request['policy'] = $request['code'];
        //记账模板
        $request['template'] = $this->getBookTemplate($request['policy']);
        $middleware = $this->getMiddlewareFromTemplate($request['template']);
        Log::debug("********  middleware ::: => ".json_encode($middleware));
        $ret = Finance::service('BookingService')
            ->setMiddleware($middleware)
            ->pass($request)
            ->runTransaction();
        //log::debug("********  ret ::: => ".json_encode($ret));
        return '0000';
    }


    /**
     * 重构getMiddleware,追加任务条件中间件
     * @param $method
     * @return array
     */
    public function getMiddleware($method)
    {
        $middleware =  parent::getMiddleware($method);
        $dbMiddleware = [];
        $code = $this->request['code'];
        Log::debug("CashierService.getMiddleware...".$code );
        $md = $this->repoPolicy->getBookingPolicyByVoucherCode($code);

        if(count($md)==0){
            Err("财务中间件没有配置:4444");
        }
        //Log::debug(json_encode($md));
        $dbMiddleware = [];
        foreach($md as $key =>$value){
            $config = 'finance.cashier.check.'.$value['policy_bean'];
            Log::info($config);
            $dbMiddleware[$value['policy_bean']] = Config::get($config);
        }
        Log::info(json_encode($dbMiddleware));
        $dbMiddleware = $dbMiddleware ? $dbMiddleware :[];

        return array_merge($middleware,$dbMiddleware);

    }

    /**
     * 获取模板数据
     */
    public function getBookTemplate($code){
        Log::debug("CashierService.getBookTemplate...");
        $ret = $this->repository->getBookingTempletByVoucherCode($code);
        if(count($ret)==0){
            Err('ACCT_TEMPLET_NO_EXIST');
        }
        $template = [];
        foreach($ret as $key => $value){
            $template[$key]= $value;
        }
        //log::debug(json_encode($template));
        return $template;

    }

    public function getMiddlewareFromTemplate($template)
    {
        //.process_bean 必须设置.系统编号为SystemBean
        Log::debug("CashierService.getMiddlewareFromTemplate...");
        $result = [];
        foreach ($template as $key => $item){
            $middleware=Config::get('finance.middle_process.'.$item['process_bean']);
            $result[$item['process_bean'].$key] = $middleware;
        }
        return $result;
    }

        // $template = [
        //     0=>[
        //         'batch_detail_id' =>'1',
        //         'account_id'      =>'SystemAccount',
        //         'process_bean' =>'MarkModel',
        //         'debit_amount_bean'   =>'',
        //         'credit_amount_bean'  =>'ChannelCost',
        //         'process_id' =>'',
        //         'debit_amount'=>'',
        //         'credit_amount' =>'12',
        //     ],
        //     1=>[
        //         'batch_detail_id' =>'2',
        //         'account_id'      =>'SystemAccount',
        //         'process_bean' =>'SystemBean',
        //         'debit_amount_bean'   =>'',
        //         'credit_amount_bean'  =>'',
        //         'process_id' =>'100200',
        //         'debit_amount'=>'500',
        //         'credit_amount' =>'',
        //     ],
        //     3=>[
        //         'batch_detail_id' =>'3',
        //         'account_id'      =>'SystemAccount',
        //         'process_bean' =>'ChannelBean',
        //         'debit_amount_bean'   =>'',
        //         'credit_amount_bean'  =>'ChannelCost',
        //         'process_id' =>'',
        //         'debit_amount'=>'',
        //         'credit_amount' =>'25',
        //     ],
        // ];
        // return $template;
}