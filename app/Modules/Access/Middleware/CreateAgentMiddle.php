<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/12
 * Time: 11:40
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\CommAgentInfoRepo;
use App\Modules\Access\Repository\CommUserRepo;
use Closure;
use Illuminate\Support\Facades\Log;

class CreateAgentMiddle extends Middleware
{
    private $agentData = '';
    private $teamAgentData = '';
    private $suffix = '0000000000';
    private $code = 'P1101';


    public $repo ;

    public function __construct(CommAgentInfoRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {
        $prefix = $this->getAgentIdMax();
        $prefix += 1;   //+1

        $data = [
            'id'               => ID(),
            'agent_name'       => $request['userName'],
            'agent_type'       => 20,
            'crp_mobile'       => $request['mobile'],
            'status'           => 0,
            'create_time'      => date('Y-m-d H:i:s'),
            'create_by'        => 'admin',
            'update_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'admin',
            'agent_id'         => $prefix.$this->suffix,
            'parent_id'        => 0,
            'parner_id'        => 0,
            'parner_name'      => '',
            'user_tariff_code' => $this->code
        ];

        Log::info("代理商自行注册:|".$data['agent_id']);

        //添加数据库
        $this->repo->insert($data);

        return $next($request);
    }

    public function getAgentIdMax()
    {
        $ret = $this->repo->getAgentIdMax();

        return substr($ret->agent_id,0,5);
    }
}