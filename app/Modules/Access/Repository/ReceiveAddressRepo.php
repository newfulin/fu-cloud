<?php

namespace App\Modules\Access\Repository;
use App\Common\Contracts\Repository;
use App\Common\Models\ReceiveAddress;

class ReceiveAddressRepo extends Repository
{
    public function __construct(ReceiveAddress $model)
    {
        $this->model = $model;
    }
    public function getRecAddressList($userId)
    {
        $re = optional($this->model
            ->select('id','name','tel','address','default','area')
            ->where('user_id',$userId)
            ->where('status','01')
            ->orderBy('default','desc')
            ->orderBy('update_time','desc')
            ->get())
            ->toArray();
        if (!$re) {
            return '';
        }
        return $re;
    }
    public function getDefAddress($userId)
    {
        $re = optional($this->model
            ->select('id','name','tel','address','default','area')
            ->where('user_id',$userId)
            ->where('status','01')
            ->orderBy('default','desc')
            ->orderBy('update_time','desc')
            ->first())
            ->toArray();
        if (!$re) {
            return '';
        }
        $re['detAddress'] = $re['area'] . $re['address'];
        unset($re['area']);
        unset($re['address']);
        return $re;
    }
    public function createRecAddress($params)
    {
        $re = $this->model->insert($params);
        return $re;
    }
    public function chooseRecAddress($request)
    {
        $re = optional($this->model
            ->select('id','name','tel','address','default','area')
            ->where('user_id',$request['user_id'])
            ->where('status','01')
            ->paginate($request['pageSize']))
            ->toArray();
        return $re['data'];
    }
    public function getRecAddress($userId)
    {
        $re = optional($this->model
            ->select('address')
            ->where('user_id',$userId)
            ->where('status','01')
            ->where('default','02')
            ->first())
            ->toArray();
        if (!$re) {
            return '';
        }
        return $re['address'];
    }
    public function getRecInfo($id)
    {
        $re = optional($this->model
            ->select('name','tel','address','default','area')
            ->where('id',$id)
            ->where('status','01')
            ->first())
            ->toArray();
        if (!$re) {
            return '';
        }
        return $re;
    }
    public function delRecAddress($id)
    {
        $data = array(
            'status' => '02',
        );
        return $this->model->where('id',$id)->update($data);
    }
    public function updRecDefault($userId)
    {
        $data = array(
            'default' => '01',
        );
        return $this->model->where('user_id',$userId)->where('status','01')->update($data);
    }
    public function updRecAddress($id,$data)
    {
        unset($data['id']);
        unset($data['user_id']);
        return $this->model->where('id',$id)->update($data);
    }
}