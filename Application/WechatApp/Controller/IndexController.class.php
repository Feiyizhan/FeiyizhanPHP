<?php
namespace WechatApp\Controller;
use Think\Controller;
use WechatApp\Model\Captcha;
use Org\Util\Date;

class IndexController extends Controller {

    private $STATUS_NONUSED = 0 ;  //未使用
    private $STATUS_INUSED = 10 ; //已使用
    public function index() {
        // 实例化数据库操作类
        $invitaionTable = D('invitation');
        // 获取分页记录
        $r = $invitaionTable->getPage();
        //                 dump($r);
        // 分页记录和分页信息赋值给视图文件
        $this->assign('lists', $r['lists']);
        $this->assign('pages', $r['pages']);
        // 指定视图标题s
        $this->assign('view_title', '首页');
        //         echo ("test");
        // 显示视图
        $this->display();
    }
    
    public function check(){
        if (IS_POST) {  // 判断当前HTTP请求是否为POST请求
            // 1. 创建数据表操作对象
            $invitationTable = D('invitation');
            // 2. 获取表单数据
            $invitationCode = I('post.InvitationCode');
//             $captcha = I('post.captcha');
            // echo $userName, ', ', $userPswd, ', ', $userImage, ', ', $captcha;
            // exit;
//             if (Captcha::checkCaptcha($captcha, Captcha::REGISTER_CAPTCHA)) {  // 验证码正确
                // 3. 检测邀请码是否存在
                $r = $invitationTable->isInvitationCodeExists($invitationCode);
                if ($r) {
                    $status =$invitationTable->getStatus($invitationCode);
                    if(0==$status){//未使用
                        session('invitationCode', $invitationCode);
                        $this->success('验证通过！', "/Index/login");
                    }else{
                        $this->error('邀请码已使用，请重新填写！',"/Index/index");
                    }
        
                }else{
                    $this->error('邀请码不正确，请重新填写！',"/Index/index");
                }
//             } else {  // 验证码不正确，要求用户重新填写
//                 $this->error('验证码不正确，请重新填写！',"/Index/index");
//             }
        } else {  // 当前HTTP请求不是POST，说明是GET请求
            // 显示视图文件
            $this->display();
        }
    }
    

    /**
     * 登录操作
     */
    public function login() {
        \Think\Log::write('===========登录开始=================','WARN');
        $invitationCode = session('invitationCode');
        $invitationTable = D('invitation');
        if(!empty($invitationCode)){  //检测邀请码状态
            
            $status =$invitationTable->getStatus($invitationCode);
            \Think\Log::write(dump($invitationTable->getLastSql(),false),'WARN');
            \Think\Log::write(dump($status,false),'WARN');
            if(false===$status){  //邀请码不存在
                session('invitationCode', null);
                $this->error('邀请码已失效，请输入新的邀请码！',"/Index/index");
                return;
            }else{
                if(0==$status){ //未使用
                    //设置状态为正在登录
                    $invitationTable->doChangeStatus($invitationCode,5);
                }else if(5==$status){
                    session('invitationCode', null);
                    $this->error('邀请码正在登录，请重新填写！',"/Index/index");
                    return;
                
                }else{
                    session('invitationCode', null);
                    $this->error('邀请码已使用，请重新填写！',"/Index/index");
                    return;
                }
            }
            
        }else{
            $this->error('请输入邀请码！',"/Index/index");
            return;
        }
        
        $uuid = '';
        $sessionId = $invitationCode;
        \Think\Log::write('测试日志信息开始===================','WARN');
        $cmd='nohup java -jar /home/www/WechatApp.jar '.$sessionId.' >/dev/null 2>&1&';
        system($cmd);
        sleep(5);
        \Think\Log::write($cmd,'WARN');
        $filename = '/home/www/Feiyizhan/WechatApp/UUID/'.strtoupper($sessionId).'/user.txt';
        if(file_exists($filename)){
            unlink($filename);
        }
        
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
            session('invitationCode', null);
            $this->error('系统出错，请试！',"/Index/index");
            return;
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
    
    
    /**
     * 显示邀请码状态明显
     */
    public function showCodeStatusDetail(){
        // 实例化数据库操作类
        $invitaionTable = D('invitation');
        // 获取分页记录
        $r = $invitaionTable->getPage();
//                 dump($r);
        // 分页记录和分页信息赋值给视图文件
        $this->assign('lists', $r['lists']);
        $this->assign('pages', $r['pages']);
        // 指定视图标题s
        $this->assign('view_title', '首页');
        //         echo ("test");
        // 显示视图
        $this->display();
    }
    
    /**
     * 解锁
     */
    public function unlock($invitationCode = ''){
        if(empty($invitationCode)){
            //更新
        }
    }
    
    public function update(){
        \Think\Log::write('测试日志信息开始===================','WARN');

        //获取当前正在运行的邀请码
        exec('ps -ef|grep java',$out);
//         dump($out);
        $loginUserList = array();  //获取当前登录的用户清单
        $date = new Date();
        foreach ($out as $val ){
            $valArray = explode(" ", $val);
//             dump($valArray);
            $invitationCode = end($valArray);

            if(strLen($invitationCode)==32){ //获取到正在运行的邀请码
                $data=array();
//                 dump(end($valArray));
                //根据邀请码，读取当前登录用户
                $filename = '/home/www/Feiyizhan/WechatApp/UUID/'.strtoupper($invitationCode).'/user.txt';
                $weiXinUser = array(); //当前邀请码的使用用户
                $date->setDate(filectime($filename));
                $loginDate= $date->format();
                $data['useDate'] = $loginDate;  //更新使用日期为用户登录日期
                if(file_exists($filename)){ //如果登录用户信息文件存在，说明已登录
                    \Think\Log::write($filename,'WARN');
                    $handle = fopen($filename, "r");
                    $val =fgets($handle);
                    fclose($handle);
                    $weiXinUser=json_decode($val);

//                     dump($weiXinUser);
//                     dump($loginDate);
                    $data['status'] = 10; //更新状态为已使用
                    $data['sessionID']=$weiXinUser->NickName;  //更新当前邀请码的使用用户
                    //增加到已登录用户清单
                    $loginUserList[]=array('invitationCode'=>$invitationCode,
                        'data' =>$data,
                    );
                    
                }else{
                    $data['status'] = 5; //更新状态为正在登录
                    //更新用户信息
                    $loginUserList[]=array('invitationCode'=>$invitationCode,
                        'data' =>$data,
                    );
                }



            }
            
            
        }
//         dump($loginUserList);
        //获取所有的邀请码记录
        $invitaionTable = D('invitation');
        $records = $invitaionTable->getAllRecords();
        foreach ($records as $data){
            $data['status'] = 0;
            $data['sessionID']='';
//             $date->setDate(time());
//             $data['useDate'] =$date->format();
            $where = array('invitationCode' => $data['invitationcode']);
            foreach ($loginUserList as $loginData){
                if($data['invitationcode']==$loginData['invitationCode']){//更新Code对应的登录信息
                    $data =$loginData['data'];
                }
            }
//             dump($data);
            $invitaionTable->doChange($where,$data);
//             echo $invitaionTable->getLastSql();
        
        }
        
        
        
        \Think\Log::write('测试日志信息结束===================','WARN');
        
        $this->success('更新成功！', "/Index/index");
    }
}

