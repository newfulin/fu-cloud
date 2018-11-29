<?php
//定义接口
interface User{
    function getDiscount();
    function getUserType();
}
//VIP用户 接口实现
class VipUser implements User{
    // VIP 用户折扣系数
    private $discount = 0.8;
    function getDiscount() {
        return $this->discount;
    }
    function getUserType() {
        return "VIP用户";
    }
}

class CommUser implements User{
    // VIP 用户折扣系数
    private $discount = 1.0;
    function getDiscount() {
        return $this->discount;
    }
    function getUserType() {
        return "普通用户";
    }
}

class Goods{
    var $price = 100;
    var $vc;
    //定义 User 接口类型参数，这时并不知道是什么用户
    function run(User $vc){
        $this->vc = $vc;
        $discount = $this->vc->getDiscount();
        $usertype = $this->vc->getUserType();
        echo $usertype."商品价格：".$this->price*$discount;
        echo "<br>";
    }
}

$display = new Goods();
$display ->run(new VipUser);	//可以是更多其他用户类型
$display ->run(new CommUser);