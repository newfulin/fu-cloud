<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/12
 * Time: 16:56
 */
$a=array(1,2,-3,4,-5,6,-7,8,9);

$ret = array_values(
        array_filter($a,function($item){
            if($item < 0 ) {
                return true;
            }
    })
);

print_r('<pre>');
print_r($ret);

//array_values 返回数组中所有的值(不保留键名)
$a=array("3"=>"UK","2"=>"CN","5"=>"USA");
print_r(array_values($a) );