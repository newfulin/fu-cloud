<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/12
 * Time: 16:14
 */

$arr = array('2','3','4','5');

//array_map(callback $callback , $arr)返回用户自定义函数作用后的数组。
//回调函数接受的参数数目应该和传递给 array_map() 函数的数组数目一致。


$ret = array_map(function($item){
    $ret = $item  * 2 ;
    $str = "   This is some <b>$ret</b> text.   ";
    $rs = htmlspecialchars(trim($str));
    return  $rs ;
}, $arr);



print_r('<pre>');
print_r($ret);

//Array
//(
//[0] => This is some <b>8</b> text.
//[1] => This is some <b>12</b> text.
//[2] => This is some <b>16</b> text.
//[3] => This is some <b>20</b> text.
//)

//删除数组中的第一个元素，并返回被删除元素的值
$a = ['id'=>'第一个元素','name'=>'a','title'=>'hello'];
echo "array_shift:-------" .array_shift($a) ;
echo '<br>剩余:<br>';

print_r($a);



$arr = [
    ['id'=>'1','name'=>'a'],
    ['id'=>'2','name'=>'b'],
    ['id'=>'3','name'=>'c']

];
$rs = array_map('array_shift',$arr);

print_r($rs);

$arr = [
    ['id'=>'1','name'=>'a'],
    ['id'=>'2','name'=>'b'],
    ['id'=>'3','name'=>'c']

];
$rs = array_map(function($item){
    return '----'.array_shift($item);
},$arr);
print_r($rs);