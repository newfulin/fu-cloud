<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/20
 * Time: 16:36
 */

namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\Store;


class StoreRepo extends Repository
{
    public function __construct(Store $model)
    {
        $this->model = $model;
    }

}