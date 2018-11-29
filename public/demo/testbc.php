<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/15
 * Time: 11:53
 */

//bcadd — 2个任意精度数字的加法计算
//2个操作数求和之后的结果以字符串返回
echo "bcadd-----<br>";
echo bcadd('5',' 1.266', 2); //5.0 有空格
echo '<br>';
echo bcadd(5,' 1.266', 2); //5.0
echo '<br>';
echo bcadd(5,'1.266 ', 2); //5.0
echo '<br>';
echo bcadd(5,'1.266', 2); //6.26 没有四舍五入
echo '<br>';
echo bcadd(5,1.266, 2); //6.26
echo '<br>';
echo bcadd(5.2,1.80, 2); //6.26
echo '<br>';

//bccomp — 比较两个任意精度的数字
//如果两个数相等返回0, 左边的数left_operand比较右边的数right_operand大返回1, 否则返回-1.
echo "bccomp-----<br>";
echo bccomp('1', '2');   // -1
echo '<br>';
echo bccomp('1.00001', '1', 3); // 0
echo '<br>';
echo bccomp('1.00001', 1, 5); // 1
echo '<br>';

//bcsub — 2个任意精度数字的减法
echo "bcsub-----<br>";
echo bcsub('1.234', 5);     // -3
echo '<br>';
echo bcsub('1.234', 5, 4);  // -3.7660
echo '<br>';




//bcdiv — 2个任意精度的数字除法计算
echo "bccomp-----<br>";
echo bcdiv('105', 6.55957, 3);  // 16.007
echo '<br>';

//bcmul — 2个任意精度数字乘法计算
echo "bcmul-----<br>";
echo bcmul('1.34747474747', '35', 3); // 47.161
echo '<br>';
echo bcmul('2', '4',2); // 8
echo '<br>';

echo 'sprintf----<br>';
echo sprintf('%.2f',123.455); //123.45
echo '<br>';
echo sprintf('%.2f',12.455);//12.46
echo '<br>';
echo number_format('233123.455',2);
echo '<br>';
echo sprintf('%.2f',round('123.455',2)); //123.46
echo '<br>';
echo number_format(4.5678, 2, '.', '');
echo '<br>';
echo sprintf('%.2f',round(33.5678,2));