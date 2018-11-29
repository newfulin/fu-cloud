<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/15
 * Time: 14:19
 */

echo preg_replace_callback('~-([a-z])~', function ($match) {
    print_r('<pre>');
    print_r($match);
    return strtoupper($match[1]);
}, 'hello-world');



echo '<br>------------------<br>';
//例一
//在函数里定义一个匿名函数，并且调用它
function printStr() {
    $func = function( $str ) {
        echo $str;
    };
    $func( 'some string' );
}

printStr();
echo '<br>------------------<br>';
//例二
//在函数中把匿名函数返回，并且调用它
function getPrintStrFunc() {
    $func = function( $str ) {
        echo $str;
    };
    return $func;
}

$printStrFunc = getPrintStrFunc();
$printStrFunc( 'some string' );

echo '<br>------------------<br>';

//例三
//把匿名函数当做参数传递，并且调用它
function callFunc( $callback ) {
    $callback( 'some string' );
}

$printStrFunc = function( $str ) {
    echo $str;
};
callFunc( $printStrFunc );
echo '<br>';

//也可以直接将匿名函数进行传递。如果你了解js，这种写法可能会很熟悉
callFunc( function( $str ) {
    echo $str;
} );

//从父作用域继承变量 use
echo '<br>------------------<br>';
function getMoney() {
    $rmb = 1;
    $dollar = 6;
    $arg="123";
    $func = function($arg) use ( $rmb , $dollar ) {
        echo $rmb;
        echo '<br>';
        echo $dollar;
        echo '<br>';
        echo $arg;
    };
    $func($arg);
}
getMoney();

echo '<br>------------------<br>';
function getMoney1() {
    $rmb = 1;
    $func = function() use ( $rmb ) {
        echo $rmb;
        echo '<br>';
        //把$rmb的值加1
        $rmb++;
    };
    $func();
    echo $rmb;
}
//原来use所引用的也只不过是变量的一个副本而已
getMoney1();

echo '<br>------------------<br>';
function getMoneyFunc() {
    $rmb = 1;
    $func = function() use ( &$rmb ) {
        echo $rmb;
        echo '<br>';
        //把$rmb的值加1
        $rmb++;
    };
    return $func;
}
//这样匿名函数就可以引用上下文的变量了。
//如果将匿名函数返回给外界，匿名函数会保存use所引用的变量，
//而外界则不能得到这些变量，这样形成‘闭包’这个概念可能会更清晰一些。
$getMoney = getMoneyFunc();
$getMoney();   //1
$getMoney();   //2
$getMoney();   //3
$getMoney();   //3
$getMoney();   //3


//bindto
echo '<br>------------------<br>';
class A {
    public static $sfoo = 1;
    private $ifoo = 2;
}
$cl1 = static function() {
    return A::$sfoo;
};
$cl2 = function() {
    return $this->ifoo;
};

$bcl1 = Closure::bind($cl1, null, 'A'); //就相当于在类里面加了个静态成员方法
$bcl2 = Closure::bind($cl2, new A(), 'A'); //相当于在类里面加了个成员方法
echo $bcl1(), "\n";
echo $bcl2(), "\n";





