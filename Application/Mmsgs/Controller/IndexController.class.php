<?php
namespace Mmsgs\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index() {
       /* $this->show('<?xml version="1.0" encoding="UTF-8"?>
<recipe type="dessert">
<recipename cuisine="american" servings="1">Ice Cream Sundae</recipename>
<preptime>5 minutes</preptime>
</recipe>', 'utf-8', 'text/xml');*/
        //echo 'index控制器的index方法被调用';
        $this->name = 'maiziedu';
        $this->assign('age', 3);  // $this->age = '3';
        $this->assign('arr', array('version' => 3, 'users' => 'zhangsan'));

        // 输出对象
        $p = new Person();
        $p->name = '张三';
        $p->age = 18;

        $this->assign('person', $p);

        $this->assign('time', time());

        $this->assign('persons', array(
            array('name'=>'zs', 'age'=>18),
            array('name'=>'ls', 'age'=>20),
        ));

        $this->display();
//         $this->show("Test");
    }

    public function add() {
        // $this->display();
    }
}

class Person {
    public $name;
    public $age;
}