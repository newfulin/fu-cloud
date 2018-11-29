<?php

//array_walk — 使用用户自定义函数对数组中的每个元素做回调处理
//bool array_walk ( array &array,callablefuncname [, mixed $userdata = NULL ] )
//$array     输入的数组。
// $funcname  回调函数，典型情况下 $funcname 接受两个参数。$array 参数的值作为第一个， 键名作为第二个。
// $userdata  如果提供了可选参数 $userdata ，将被作为第三个参数传递给 $funcname。

$fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");

//传引用，改变了所传参数组
function test_alter(&$item1, $key, $prefix)
{
    $item1 = "$prefix: $item1";
}

function test_print($item2, $key)
{
    echo "$key. $item2<br />\n";
}

echo "Before ...:\n";
//单数组
array_walk($fruits, 'test_print');

//带额外参数
array_walk($fruits, 'test_alter', 'fruit');
echo "... and after:\n";

array_walk($fruits, 'test_print');


//Before ...:
//d. lemon
//a. orange
//b. banana
//c. apple
// ... and after:
//d. fruit: lemon
//a. fruit: orange
//b. fruit: banana
//c. fruit: apple

print_r('<pre>');
print_r($fruits);

//Array
//(
//[d] => fruit: lemon
//[a] => fruit: orange
//[b] => fruit: banana
//[c] => fruit: apple
//)

$str = '123';
array_walk($fruits,function(&$item,$key,$str){
    $item = $str . '-------' .$key . '-----' . $item;
},$str);

print_r($fruits);
