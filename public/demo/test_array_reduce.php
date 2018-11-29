<?php

$arr = array('1','2','3'); //计算数组中数字的和
$sum = 0;
foreach($arr as $v){ //使用 foreach循环计算
    $sum += $v;//
}
echo $sum ;



//array_reduce( $arr , callable $callback ) 使用回调函数迭代地将数组简化为单一的值
//pipeline
$ret = array_reduce($arr , function($result , $v){
    return ($result + $v);
});

echo '<br>';
echo $ret;


//6
//6
