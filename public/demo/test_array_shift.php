<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/27
 * Time: 15:59
 */

$arr = [
    '1' =>[
        'aa'=>'b',
        'cc'=>'asdfaf'
    ],
    '2' =>'asdfasdf',
    '3' =>'asdfasdfasdfasdfasdf',
    '4' =>'123123123'

];

$shift = array_shift($arr);


print_r('<pre>');
print_r($arr);