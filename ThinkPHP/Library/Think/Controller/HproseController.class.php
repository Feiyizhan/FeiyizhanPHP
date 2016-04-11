<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Controller;  
  
  
/** 
 * ThinkPHP Hprose控制器类 
 */  
class HproseController {  
  
  
    protected $allowMethodList = '';  
    protected $crossDomain = false;  
    protected $P3P = false;  
    protected $get = true;  
    protected $debug = false;  
    public $onBeforeInvoke = null;  
    public $onAfterInvoke = null;  
    public $onSendHeader = null;  
    public $onSendError = null;  
    public static $methods = array();  
  
  
    /** 
     * 架构函数 
     * @access public 
     */  
    public function __construct() {  
        //控制器初始化  
        if (method_exists($this, '_initialize'))  
            $this->_initialize();  
        //导入类库  
        Vendor('Hprose.HproseHttpServer');  
        //实例化HproseHttpServer  
        $server = new \HproseHttpServer();  
        if ($this->allowMethodList) {  
            $methods = $this->allowMethodList;  
        } else {  
            $methods = get_class_methods($this);  
            $methods = array_diff($methods, array('__construct', '__call', '_initialize', 'onBeforeInvoke', 'onAfterInvoke', 'onSendHeader', 'onSendError'));  
        }  
        self::$methods = $methods;  
        $server->addMethods($methods, $this);  
        if ($this->debug) {  
            $server->setDebugEnabled(true);  
        }  
        // Hprose设置  
        //是否跨域访问  
        $server->setCrossDomainEnabled($this->crossDomain);  
        //是否发送P3P的http头，这个头的作用是让IE允许跨域接收的Cookie  
        $server->setP3PEnabled($this->P3P);  
        //禁止服务器接收GET请求 参数设置为false即可  
        $server->setGetEnabled($this->get);  
  
  
        if (method_exists($this, 'onBeforeInvoke')) {  
            $server->onBeforeInvoke = '\\Think\\Controller\\HproseController::onBeforeInvoke';  
        }  
        if (method_exists($this, 'onAfterInvoke')) {  
            $server->onAfterInvoke = '\\Think\\Controller\\HproseController::onAfterInvoke';  
        }  
        if (method_exists($this, 'onSendHeader')) {  
            $server->onSendHeader = '\\Think\\Controller\\HproseController::onSendHeader';  
        }  
        if (method_exists($this, 'onSendError')) {  
            $server->onSendError = '\\Think\\Controller\\HproseController::onSendError';  
        }  
        // 启动server  
        $server->start();  
    }  
  
  
    /** 
     * 服务器端发布的方法被调用前，onBeforeInvoke事件被触发 
     * @param type $name name为客户端所调用的方法名， 
     * @param type $args args为方法的参数， 
     * @param type $byRef byRef表示是否是引用参数传递的调用 
     */  
    public static function onBeforeInvoke($name = '', $args = array(), $byRef = false) {  
        \Think\Log::write('$methods:' . json_encode(self::$methods),'INFO');  
        \Think\Log::write('event:' . __FUNCTION__ . PHP_EOL . '$name:' . json_encode($name) . PHP_EOL . '$args:' . json_encode($args) . PHP_EOL . '$byRef:' . json_encode($byRef),'INFO');  
    }  
  
  
    /** 
     * 当服务器端发布的方法被成功调用后，onAfterInvoke事件被触发 
     * 当调用发生错误时，onAfterInvoke事件将不会被触发。如果在该事件中抛出异常，则调用结果不会被返回，客户端将收到此事件抛出的异常 
     * @param type $name name为客户端所调用的方法名， 
     * @param type $args args为方法的参数， 
     * @param type $byRef byRef表示是否是引用参数传递的调用 
     * @param type $result 调用结果 
     */  
    public static function onAfterInvoke($name = '', $args = array(), $byRef = false, $result = '') {  
        \Think\Log::write('event:' . __FUNCTION__ . PHP_EOL . '$name:' . json_encode($name) . PHP_EOL . '$args:' . json_encode($args) . PHP_EOL . '$byRef:' . json_encode($byRef) . PHP_EOL . '$result:' . json_encode($result));  
    }  
  
  
    /** 
     * 当服务器返回响应头部时，onSendHeader事件会被触发 
     */  
    public static function onSendHeader() {  
          
    }  
  
  
    /** 
     * 当服务器端调用发生错误，或者在onBeforeInvoke、onAfterInvoke事件中抛出异常时，该事件被触发 
     * 您可以在该事件中作日志记录，但该事件中不应再抛出任何异常 
     * @param type $error 
     */  
    public static function onSendError($error) {  
        \Think\Log::write('$methods:' . json_encode(self::$methods));  
        \Think\Log::write('event:' . __FUNCTION__);  
        \Think\Log::write('$error:' . json_encode($error));  
    }  
  
  
    /** 
     * 魔术方法 有不存在的操作的时候执行 
     * @access public 
     * @param string $method 方法名 
     * @param array $args 参数 
     * @return mixed 
     */  
    public function __call($method, $args) {  
        \Think\Log::write('$methods:' . json_encode(self::$methods));  
        \Think\Log::write('event:' . __FUNCTION__);  
    }  
  
  
}  
