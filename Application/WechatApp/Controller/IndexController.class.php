<?php
namespace WechatApp\Controller;
use Think\Controller;
use WechatApp\Model\Captcha;

class IndexController extends Controller {

    private $STATUS_NONUSED = 0 ;  //未使用
    private $STATUS_INUSED = 10 ; //已使用
    public function index() {
        if (IS_POST) {  // 判断当前HTTP请求是否为POST请求
            // 1. 创建数据表操作对象
            $invitationTable = D('invitation');
            // 2. 获取表单数据
            $invitationCode = I('post.InvitationCode');
            $captcha = I('post.captcha');
            // echo $userName, ', ', $userPswd, ', ', $userImage, ', ', $captcha;
            // exit;
            if (Captcha::checkCaptcha($captcha, Captcha::REGISTER_CAPTCHA)) {  // 验证码正确
                // 3. 检测邀请码是否存在
                $r = $invitationTable->isInvitationCodeExists($invitationCode);
                
                \Think\Log::write(dump($r,false),'WARN');
                if ($r) {
                    \Think\Log::write('邀请码验证通过','WARN');
                    session('invitationCode', $invitationCode);
                    session('status',5); 
                    $this->success('验证通过！', "Index/login");
                }else{
                    \Think\Log::write('邀请码验证不通过','WARN');
                    $this->error('邀请码不正确，请重新填写！');
                }
            } else {  // 验证码不正确，要求用户重新填写
                $this->error('验证码不正确，请重新填写！');
            }
        } else {  // 当前HTTP请求不是POST，说明是GET请求
            // 显示视图文件
            $this->display();
        }
    }
    
    

    public function login() {
        $invitationCode = session('invitationCode');
        $invitationTable = D('invitation');
        if(!empty($invitationCode)){  //检测邀请码状态
            
            $status =$invitationTable->getStatus($invitationCode);
            if(false===$status){  //邀请码不存在
                $this->error('邀请码已失效，请输入新的邀请码！',"index");
                session('status',0);
                return;
            }else{
                if(0==$status){ //未使用
                    //先锁定
                    $invitationTable->doChangeStatus($invitationCode,10);
                    session('status',10);
                }else{
                    $this->error('邀请码已使用，请重新填写！',"index");
                    session('status',0);
                    return;
                }
            }
            
        }else{
            $this->error('请输入邀请码！',"index");
            session('status',0);
            return;
        }
        
        $uuid = '';
        $sessionId = $invitationCode;
        \Think\Log::write('测试日志信息开始===================','WARN');
        $cmd='nohup java -jar /home/www/WechatApp.jar '.$sessionId.' >/dev/null 2>&1&';
        system($cmd);
        sleep(5);
        \Think\Log::write($cmd,'WARN');
        $filename = '/home/www/Feiyizhan/WechatApp/UUID/'.strtoupper($sessionId).'/system.txt';
        \Think\Log::write($filename,'WARN');
        $handle = fopen($filename, "r");
        $val =fgets($handle);
        fclose($handle);
        \Think\Log::write($val,'WARN');
        
        if(!empty($val)){
            $uuid =explode(" : ", $val)[1];
        }else{
            //解锁邀请码
            $invitationTable->doChangeStatus($invitationCode,0);
            session('status',0);
        }
        \Think\Log::write($uuid,'WARN');
        
        \Think\Log::write('测试日志信息结束===================','WARN');
        //         $this->show(print_r($out));
        $this->assign('uuid',$uuid);
        $this->display();
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
    
    /**
     * 生成邀请码
     */
    public function generateCode(){
        //生成邀请码Module对象
        $invitaionTable = D('invitation');
        $codeList = array();
        //批量生成10个
        for($i=0;$i < 10;$i++){
           $code = $invitaionTable->generateInvitationCode();
           $codeList[$i]=$code;
        }
        dump($codeList);
         $this->assign('codeList',$codeList);
         $this->display();
    }
    
}

