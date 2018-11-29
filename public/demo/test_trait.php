<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/15
 * Time: 15:11
 */
trait first_trait {
    public  $c = '123';
    function first1_method() { echo 'first1_method<br>';}
    function first2_method() { echo 'first2_method<br>'; }
}
trait second_trait {
    function second1_method() { echo 'second1_method<br>';}
    function second2_method() { echo 'second2_method<br>'; }

}

class first_class {
// 注意这行，声明使用 first_trait
    use first_trait, second_trait;
}
$obj = new first_class();
// Executing the method from trait
echo $obj->c;
echo '<br>';
$obj->first1_method(); // valid
$obj->second2_method(); // valid

echo '<br>---------------<br>';


trait three_trait {
    function first1_method() { echo 'first1_method<br>';}
    function first2_method() { echo 'first2_method<br>'; }
}
trait four_trait {
    use three_trait;
    function second1_method() { echo 'second1_method<br>';}
    function second2_method() { echo 'second2_method<br>'; }

}

class second_class {
    use four_trait;
}
$obj = new second_class();
// Executing the method from trait
$obj->first1_method(); // valid
$obj->second2_method(); // valid