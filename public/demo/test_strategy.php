<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/15
 * Time: 14:57
 */
interface FlyBehavior{
    public function fly();
}
class FlyWithWings implements FlyBehavior{
    public function fly(){
        echo "Fly With Wings <br>";
    }
}
class FlyWithNo implements FlyBehavior{
    public function fly(){
        echo "Fly With No Wings <br>";
    }
}

class Duck
{
    private $_flyBehavior;

    public function performFly()
    {
        $this->_flyBehavior->fly();
    }

    public function setFlyBehavior(FlyBehavior $behavior)
    {
        $this->_flyBehavior = $behavior;
    }
}
class RubberDuck extends Duck{
}

// Test Case
$duck = new RubberDuck();
/*  想让鸭子用翅膀飞行 */
$duck->setFlyBehavior(new FlyWithWings());
$duck->performFly();

/*  想让鸭子不用翅膀飞行 */
$duck->setFlyBehavior(new FlyWithNo());
$duck->performFly();

//总的来说，我们在开发中的设计原则如下:
//1.找出应用中可能需要变化之处，把它们独立出来，不要和那些不需要变化的代码混在一起;
//2.针对接口编程，不针对实现编程;
//3.多用组合，少用继承;


echo '<br>--------------<br>';

interface  Strategy{
    function wayToSchool();
}
class BikeStrategy implements Strategy{
    function wayToSchool(){
        echo "骑自行车去上学";
    }
}
class BusStrategy implements Strategy{
    function wayToSchool(){
        echo "乘公共汽车去上学";
    }
}
class TaxiStrategy implements Strategy{
    function wayToSchool(){
        echo "骑出租车去上学";
    }
}

//环境角色
class Context{
    private $strategy;
    //获取具体策略
    function getStrategy($strategyName){
        try{
            $strategyReflection = new ReflectionClass($strategyName);
            $this->strategy = $strategyReflection->newInstance();

        }catch(ReflectionException $e){
            $this->strategy = "";
        }
    }

    function goToSchool(){
        $this->strategy->wayToSchool();
        // var_dump($this->strategy);
    }
}
//测试
$context = new Context();
$context->getStrategy("BusStrategy");
$context->goToSchool();



class DB {

    public $data = [];

    public function select($str){
        $this->data['select'] = $str;
        return $this;
    }

    public function from($str) {
        $this->data['from'] = $str;
        return $this;
    }

    public  function __call($name,$arg){
        echo '<br>';
        echo  $name ;
        print_r('<pre>');
        print_r($arg);
        exit();
    }

    public static function __callStatic($name, $arguments){
        echo '<br>';
        echo  $name ;
        print_r('<pre>');
        print_r($arguments);
        exit();
    }



}
//class_alias('DDDDD_DD_DB','DB');

//DB::getMyfun('runn static context');

$db= new DB;
$db->fun('call function ');

