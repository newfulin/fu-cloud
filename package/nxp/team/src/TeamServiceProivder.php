<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/5/16
 * Time: 18:35
 */
namespace Nxp\Team;

use Illuminate\Support\ServiceProvider;

class TeamServiceProivder extends ServiceProvider {

    public function register()
    {
        app()->singleton('nxp-team',function(){
            return app()->make(Team::class);
        });
    }

    public function boot()
    {
        //
    }

}