<?php
namespace WechatApp\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index() {

        $this->display();
    }

    public function login() {
        $uuid = '';
        $sessionId = 'feiyizhan';
        \Think\Log::write('测试日志信息开始===================','WARN');
        $cmd='nohup java -jar /home/www/WechatApp.jar '.$sessionId.' >/dev/null 2>&1&';
        system($cmd);
        sleep(3);
        \Think\Log::write($cmd,'WARN');
        $filename = '/home/www/Feiyizhan/WecharApp/UUID/'.strtoupper($sessionId).'/system.txt';
        \Think\Log::write($filename,'WARN');
        $handle = fopen($filename, "r");
        $val =fgets($handle);
        fclose($handle);
        \Think\Log::write($val,'WARN');
        
        if($val!==""){
            $uuid =explode(" : ", $val)[1];
        }
        \Think\Log::write($uuid,'WARN');
        
        \Think\Log::write('测试日志信息结束===================','WARN');
        //         $this->show(print_r($out));
        $this->assign('uuid',$uuid);
    }
    
    
    /**
     * 创建验证码图片
     * @param  string $atype 创建的是哪一个操作的验证码，默认为'register'
     * @return [type]        [description]
     */
    public function captcha($atype = 'register') {
        switch ($atype) {
            case 'register':
                Captcha::createCaptcha(Captcha::REGISTER_CAPTCHA);
                break;
            case 'login':
                Captcha::createCaptcha(Captcha::LOGIN_CAPTCHA);
                break;
            default:
                Captcha::createCaptcha(Captcha::REGISTER_CAPTCHA);
                break;
        }
    }
}

