<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Pipeline\Pipeline;

//原始数据 ---> 【前置管道】 ---> 目标处理逻辑 ---> 【后置管道】 ---> 结果数据
//通过这种机制，可以将目标处理逻辑与过滤、认证等机制的代码分离开来，
//这样我们就更容易让代码清晰和易于维护。
//通过前置、后置管道，在其中 “放置” 我们需要过滤的逻辑即可，
//如上述代码，虽然只是一个简单的示例，就已经能够看得出，整个流程的动向，
//譬如我们在上面示例中准备了四个过滤组件（中间件）：
// pipe1、pipe2、pipe3、pipe4，pipe5其中 1、2、4 ,5是前置，3 为后置。

//输入的原始数据为 5，
//执行过程首先通过 1 号过滤组件，
//然后是 2 号，
//再然后是 4 号，
//到达目标处理逻辑后，
//再通过 3 号过滤组件，最终输出结果。

//输入原始数据为 7，
//同样是先经过 1 号过滤组件，
//随后是 2 号，不过在 2 号中，
//直接返回了结果，这意味着过程被拦截，
//不再继续向下传递数据，至此结束并返回结果。
//https://laravel-china.org/articles/2769/laravel-pipeline-realization-of-the-principle-of-single-component

$pipe1 = function ($poster, Closure $next) {
    $poster += 1;
    echo "pipe1: $poster<br>";
    return $next($poster);
};

$pipe2 = function ($poster, Closure $next) {
    if ($poster > 7) {
        return $poster;
    }
    $poster += 3;
    echo "pipe2: $poster<br>";
    return $next($poster);
};

$pipe3 = function ($poster, Closure $next) {
    $result = $next($poster);
    echo "pipe3: $result<br>";
    return $result * 2;

//    $poster *= 2;
//    echo "pipe3: $poster<br>";
//    return $next($poster);


};

$pipe4 = function ($poster, Closure $next) {
    $poster += 2;
    echo "pipe4 : $poster<br>";
    return $next($poster);
};

$pipe5 = function ($poster, Closure $next) {
    $poster += 5;
    echo "pipe5 : $poster<br>";
    return $next($poster);
};

$pipes = [$pipe1, $pipe2, $pipe3, $pipe4,$pipe5];

function dispatcher($poster, $pipes)
{
    $ret = (new Pipeline)
            ->send($poster)
            ->through($pipes)
            ->then(function ($poster){
                echo "received: $poster<br>";
                return 3;
            });

    echo "result : " .$ret .'<br>';
}

echo "==> action 1:<br>";
dispatcher(5, $pipes);

echo "==> action 2:<br>";
dispatcher(7, $pipes);