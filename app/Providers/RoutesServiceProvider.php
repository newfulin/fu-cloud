<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/23
 * Time: 09:00
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RoutesServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerAccessRoutes();
        $this->registerCallbackRoutes();
        $this->registerTestRoutes();
        $this->registerPmsRoutes();
        $this->registerHeadlineRouters();
    }

    public function boot()
    {

    }

    public function registerAccessRoutes()
    {
        app()->router->group([
            'namespace' => 'App\Modules\Access\Controller',
        ], function ($router) {
            require __DIR__ . '/../Routes/access.php';
        });
    }

    public function registerCallbackRoutes()
    {
        app()->router->group([
            'namespace' => 'App\Modules\Callback\Controller',
        ], function ($router) {
            require __DIR__ . '/../Routes/callback.php';
        });
    }

    public function registerTestRoutes()
    {
        app()->router->group([
            'namespace' => 'App\Modules\Test\Controller',
        ], function ($router) {
            require __DIR__ . '/../Routes/test.php';
        });
    }

    public function registerPmsRoutes(){
        app()->router->group([
            'namespace' => 'App\Modules\Pms\Controller',
        ], function ($router) {
            require __DIR__ . '/../Routes/pms.php';
        });
    }

    public function registerHeadlineRouters(){
        app()->router->group([
            'namespace' => 'App\Modules\Headline\Controller',
        ], function ($router) {
            require __DIR__ . '/../Modules/Headline/Routes/headline.php';
        });
    }

}