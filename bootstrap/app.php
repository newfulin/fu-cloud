<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);


//开启门店模式
$app->withFacades();

$app->withEloquent();



/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

//异常处理,重置响应报文
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Handlers\ExceptionsHandler::class
);


$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware(
    [
        //后置的报文响应中间件
        //允许跨域中间件
        App\Middleware\CORSMiddleware::class,
        //重组报文中间件
        App\Middleware\Response::class
    ]
);


$app->routeMiddleware([
    'auth' => App\Middleware\Authenticate::class,
    'permission' => App\Middleware\Permission::class
]);

/*
|--------------------------------------------------------------------------
| Register Services Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Services providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);

$app->register(App\Providers\AuthServiceProvider::class);

$app->register(App\Providers\EventServiceProvider::class);

$app->register(\Illuminate\Redis\RedisServiceProvider::class);


//通过路由服务来注册路由
$app->register(App\Providers\RoutesServiceProvider::class);
//版本控制服务
$app->register(App\Providers\VersionServiceProvider::class);

//注册Access服务
$app->register(\App\Modules\Access\AccessProvider::class);
//注册交易服务
$app->register(\App\Modules\Transaction\TransactionProvider::class);
//财务服务
$app->register(\App\Modules\Finance\FinanceProvider::class);
//回调模块
$app->register(\App\Modules\Callback\CallbackProvider::class);
//图片合成
$app->register(\Intervention\Image\ImageServiceProvider::class);
////二维码生成
$app->register(SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class);
//pms 接口调用
$app->register(\App\Modules\Pms\PmsProvider::class);



//其它需要的package
$app->register(Nxp\Wechat\WechatServiceProivder::class);

//团队关系
$app->register(Nxp\Team\TeamServiceProivder::class);
// 通用
//$app->register(Nxp\General\GeneralServiceProivder::class);


$app->register(\App\Modules\Test\TestProvider::class);

return $app;
