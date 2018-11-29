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
                $auth_token = 'eyJpZCI6IjExNTkxMTkzNDg3MzcwODgwMDEiLCJuYW1lIjoiMTMzNDUwNTYxODkiLCJyb2xlIjoiUDE1MDEiLCJpYXQiOjE1NDI3MDM0NjIsImV4cCI6MTU0NTI5NTQ2Mn0=.6f26d2f7087b05cfe6487c85c67de137';
            }
            $claims = Token()->verifyToken($auth_token);
            $user = new CommUserInfo();
            $user->id = $claims->id;
            $user->claims= $claims;
            return $user;
        });
    }
}
