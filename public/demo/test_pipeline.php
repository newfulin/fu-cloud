<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/12
 * Time: 17:56
 */

class TransData
{

    public $request = [];
    public $response = [];

    public function setResp($resp, $value)
    {
        $this->response[$resp] = $value;

    }

}

class Middle1
{
    public function handle($request, Closure $next)
    {
        $request->setResp('middle1', $request->request['businessCode']);
        return $next($request);

    }
}

class Middle2
{
    public function handle($request, Closure $next)
    {
        $request->setResp('middle2', $request->request['userId']);
        return $next($request);

    }
}

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);


print_r('<pre>');
$TransData = new TransData();
$TransData->request = [
    'processId' => '123123',
    'transAmt' => '23.00',
    'businessCode' => 'A3233',
    'userId' => '456456'
];

$middle = [
    'Middle1',
    'Middle2'
];
$process = function ($request) {
    $request->setResp('PRCESS', 'last');
    return $request;
};
$pipeLine = new \Illuminate\Pipeline\Pipeline($app);
$ret = $pipeLine->send($TransData)->through($middle)->then($process);


print_r($ret);


exit();


print_r('<pre>');
print_r($app);
exit();