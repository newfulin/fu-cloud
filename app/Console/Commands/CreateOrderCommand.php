<?php namespace App\Console\Commands;

use App\Common\Models\CommUserInfo;
use App\Common\Models\GoodsInfo;
use App\Common\Models\GoodsOrder;
use App\Modules\Access\Access;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Log;
class CreateOrderCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'order:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CreateOrderController';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //这里做任务的具体处理，可以用模型
            Log::info('任务调度--------生成虚拟订单');
            $ary = [];
            Log::info('获取所有正上架的产品-----');
            //获取所有正上架的产品
            $goods_id = optional(GoodsInfo::select('id')
                ->where('status','10')
//                ->where('start_time','<',date('Y-m-d H:i:s'))
//                ->where('end_time','>',date('Y-m-d H:i:s'))
                ->get())->toArray();
            //查询订单小于10的产品id
            Log::info('查询订单小于10的产品id-----');
            $list = ['20','25','60'];
            foreach($goods_id as $k => $v){
                $count = GoodsOrder::select('goods_id')
                    ->where('goods_id',$v)
                    ->whereIn('state',$list)
                    ->count();
                Log::info('订单数量111111'.$count);
                if($count < 3){
                    array_push($ary,$v);
                }
            }
            //获取虚拟用户
            Log::info('获取虚拟用户-----');
            $id = CommUserInfo::select('id')->where('id','<','1158514104256919553')
                ->where('unionid','!=',NULL)
                ->where('user_name','!=',NULL)
                ->inRandomOrder()
                ->limit(1)->get();
            if($id){
                    $ids = $ary[array_rand($ary)]['id'];
            //生成虚拟订单
            Log::info('生成虚拟订单-----');
            Access::service('GoodsOrderService')
                ->with('goods_id',$ids)
                ->with('number',1)
                ->with('user_id',$id[0]['id'])
                ->run('generateVirtualGoodsOrder');
            Access::service('GoodsOrderService')
            ->with('goods_id',$ids)
            ->run('updateSales');
            }
    }

}