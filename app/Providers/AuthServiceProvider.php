<?php

namespace App\Providers;

use App\Common\Models\CommUserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            $auth_token = $request->header("autoken")
                ? $request->header("authToken")
                : $request->input('authToken');

            //开发模式 默认token 默认用户id ’1459125212883834264‘
            if(env('APP_ENV') == 'development'){
                $auth_token = 'eyJpZCI6IjExODg1MjAxNTM1MDAwNjQ3NjkiLCJuYW1lIjoiIiwicm9sZSI6IlAxMTAxIiwiaWF0IjoxNTQ1NjQzNjMzLCJleHAiOjE1NDgyMzU2MzN9.1795e9e8524371599728b2ea44c8ffa3';
            }
            $claims = Token()->verifyToken($auth_token);
            $user = new CommUserInfo();
            $user->id = $claims->id;
            $user->claims= $claims;
            return $user;
        });
    }
}
