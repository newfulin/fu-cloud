<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/31
 * Time: 11:50
 */

namespace App\Modules\Access\Listener;
use App\Modules\Access\Access;
use App\Modules\Access\Events\AnalysisEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

// implements ShouldQueue
class AnalysisListener implements ShouldQueue {
	/**
	 * 统计监听
	 */
	public function handle(AnalysisEvent $event) {
		Log::info('AnalysisListener.统计监听');
		Log::info(json_encode($event->object));
		$message = Access::service('AnalysisService')
			->with('IP', $event->object->IP) //客户端IP
			->with('Type', $event->object->Type) //分类
			->with('Id', $event->object->Id) //关联Id
			->with('UserId', $event->object->UserId) //用户ID
			->with('Name', $event->object->Name) //统计主题
			->with('Desc', $event->object->Desc) //统计描述(如果需要)
			->with('Remark', $event->object->Remark) //备注(区分获取分享信息与分享)
			->with('OpenId', $event->object->OpenId) //微信用户唯一标识(统计查看次数需要)
			->run('handle');
	}
}