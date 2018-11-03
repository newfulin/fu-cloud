<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/5/30
 * Time: 09:33
 */
namespace App\Modules\Test\Repository;

use App\Common\Contracts\Repository;
use App\Modules\Access\Repository\CommUserRepo;
use Illuminate\Support\Facades\Cache;

class TestRepo extends Repository {

    protected $model;

    public function __construct(CommUserRepo $model)
    {
        $this->model = $model;
    }


    public function getTest()
    {
        $id = '1090384697256833280';
        $key = 'CommUser_'.$id;
        $minutes = 1;
        return Cache::remember($key,$minutes,function() use ($id){
            return $this->find($id);
        });

    }
    
    
}