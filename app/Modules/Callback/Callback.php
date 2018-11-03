<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:09
 */
namespace App\Modules\Callback ;

use Illuminate\Support\Facades\Facade;

class Callback extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public static function getFacadeAccessor()
    {
        return 'app-callback';
    }


}