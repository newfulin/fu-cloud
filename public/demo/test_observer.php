<?php
/**
 * php设计模式-观测者模式
 * 概念:其实观察者模式这是一种较为容易去理解的一种模式吧，它是一种事件系统，意味
 *          着这一模式允许某个类观察另一个类的状态，当被观察的类状态发生改变的时候，
 *          观察类可以收到通知并且做出相应的动作;观察者模式为您提供了避免组件之间
 *          紧密耦合的另一种方法
 * 关键点:
 *        1.被观察者->追加观察者;->一处观察者;->满足条件时通知观察者;->观察条件
 *        2.观察者 ->接受观察方法

 */


abstract class Subject implements SplSubject {
    //注册观察者
    private $observers = NULL;
    public $obdata = NULL;
    public function __construct(){
        //SplObjectStorage
        //SplObjectStorage类实现了以对象为键的映射（map）或对象的集合（
        //如果忽略作为键的对象所对应的数据）这种数据结构。
        //这个类的实例很像一个数组，但是它所存放的对象都是唯一的。
        //这个特点就为快速实现 Observer 设计模式贡献了不少力量，因为我们不希望同一个观察者被注册多次。
        //该类的另一个特点是，可以直接从中删除指定的对象，而不需要遍历或搜索整个集合。
        //SplObjectStorage类的实例之所以能够只存储唯一的对象，
        //是因为其 SplObjectStorage::attach()方法的实现中先判断了指定的对象是否已经被存储
        $this->observers = new SplObjectStorage();
    }


    /**
     * 追加观察者
     * @param SplObserver $observer 观察者
     * @param int $type 观察类型
     */
    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    /**
     * 去除观察者
     * @param SplObserver $observer 观察者
     * @param int $type 观察类型
     */
    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    /**
     * 满足条件时通知观察者
     * @param int $type 观察类型
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this,$this->obdata);
        }
    }
}


/**
 * 用户登陆-诠释观察者模式
 */
class User extends Subject {

    public $name = null;

    public function addUser()
    {
        //执行sql
        //数据库插入成功
        $res = true;
        $this->obdata = "123123";
        return $res;
    }
    public function editUser()
    {
        //执行sql
        //数据库更新成功
        $res = true;
        return $res;
    }
}

abstract  class Observer implements  SplObserver{

    public function update(SplSubject $subject)
    {
        if (func_num_args() === 2) {
            $this->handleObserver($subject,func_get_arg(1));
        }else{
            $this->handleObserver($subject);
        }

    }
    abstract protected function handleObserver($subject,$obdata=null);
}

/**
 * 观察者-发送邮件
 */
class Send_Mail extends Observer
{
    protected $event = NULL;

    public function __construct(ObEvent $event)
    {
        $this->event = $event;
    }
    public function handleObserver($subject,$obdata=null)
    {
        $params = [
            'arg1' =>'123123',
            'arg2' =>'asdfasdf',
            'obdata' =>$obdata
        ];
        $this->event->handleObEvent($subject,$params);

    }
}

interface ObEvent {
    public function handleObEvent($subject);
}
class UpdateForMail implements ObEvent {
    public function  handleObEvent($subject)
    {
        if (func_num_args() === 2) {
            $params = func_get_arg(1);
            print_r('<pre>');
            print_r($subject);
            print_r($params);
        }
    }
}


$user =  new User();
$user->addUser();
//发送更新邮件
$user->attach(new Send_Mail(new UpdateForMail()));
//调用通知观察者
$user->notify();