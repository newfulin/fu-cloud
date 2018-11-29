<?php

namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Repository\ReceiveAddressRepo;

class UserRelatedService extends Service
{
    public function getRules()
    {
        return [

        ];
    }
    protected $address;
    public function __construct(ReceiveAddressRepo $address)
    {
        $this->address = $address;
    }
    // 获取订单列表
    public function getRecAddressList($request)
    {
        $userId = $request['userId'];
        $re = $this->address->getRecAddressList($userId);
        return $re;
    }
    // 获取默认收货地址
    public function getDefAddress($request)
    {
        $re = $this->address->getDefAddress($request['userId']);

        return $re;

    }
    public function createRecAddress($request)
    {
        if ($request['default'] == '02') {
            $this->address->updRecDefault($request['user_id']);
        }
        $time = time();
        $params = $request;
        $params['create_time'] = date('Y-m-d H:m:s', $time);
        $params['create_by'] = 'system';
        $params['update_time'] = date('Y-m-d H:m:s', $time);
        $params['update_by'] = 'system';
        $re = $this->address->createRecAddress($params);
        if ($re == false) {
            Err('收货地址添加失败', '7777');
        }
        return $request;
    }

    public function chooseRecAddress($request)
    {
        $re = $this->address->chooseRecAddress($request);
        foreach ($re as $k => $v) {
            $v['area'] = str_replace('-', '', $v['area']);
            $re[$k]['detAddress'] = $v['area'] . $v['address'];
            unset($re[$k]['area']);
            unset($re[$k]['address']);
        }
        return $re;
    }

    public function delRecAddress($request)
    {
        $re = $this->address->delRecAddress($request['id']);
        return $re;
    }

    public function updRecAddress($request)
    {
        if ($request['default'] == '02') {
            $this->address->updRecDefault($request['user_id']);
        }
        $re = $this->address->updRecAddress($request['id'], $request);
        return $re;
    }

    public function setRecDefault($request)
    {
        $this->address->updRecDefault($request['user_id']);
        $request['default'] ='02';
        $re = $this->address->updRecAddress($request['id'], $request);
        return $re;
    }

    public function getRecInfo($request)
    {
        $re = $this->address->getRecInfo($request['id']);
        return $re;
    }
}