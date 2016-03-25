<?php
namespace Mmsgs\Controller;
use Think\Controller;
use Mmsgs\Model\Captcha;

class UserController extends Controller {
    public function register() {
      if (IS_POST) {  // 判断当前HTTP请求是否为POST请求
         // 1. 创建数据表操作对象
         $userTable = D('users');
         // 2. 获取表单数据
         $userName = I('post.username');
         $userPswd = I('post.password');
         $userImage = I('post.image');
         $captcha = I('post.captcha');
         // echo $userName, ', ', $userPswd, ', ', $userImage, ', ', $captcha;
         // exit;
         if (Captcha::checkCaptcha($captcha, Captcha::REGISTER_CAPTCHA)) {  // 验证码正确
           // 3. 注册
           $r = $userTable->doUserRegister($userName, $userPswd, $userImage);
           if ($r) {
              $this->success('用户注册成功！', "../../user/login");
           }
        } else {  // 验证码不正确，要求用户重新填写
            $this->error('验证码不正确，请重新填写！');
        }
      } else {  // 当前HTTP请求不是POST，说明是GET请求
        // 显示视图文件
        $this->assign('view_title', '用户注册');
        $this->display();
      }
    }

    public function login() {
      if (IS_POST) {  // 用户已经提交表单数据
          // 1. 创建数据表操作对象
         $userTable = D('users');
         // 2. 获取表单数据
         $userName = I('post.username');
         $userPswd = I('post.password');
         // 3. 判断用户名和密码的有效性
         if ($userTable->isValidUser($userName, $userPswd)) { // 用户名和密码正确
            // 3.1 把用户名信息和用户id信息放到session中
            session('loginedUser', $userName);
            session('loginedUserId', $userTable->getUserIdByUserName($userName));
            // 3.2 跳转到首页
            $this->success('用户登录成功！', "../msg/index");
         } else {
            $this->error('用户名或密码错误，请重新填写！');
          }
      } else {
        // 显示视图文件
        $this->assign('view_title', '用户登录');
        $this->display();
      }
    }

    public function logout() {
      // 销毁session
      session('loginedUser', null);
      // 跳转到首页
      $this->redirect("../../msg/index/");
    }

    public function changepswd() {
        // 1. 创建数据表操作对象
       $userTable = D('users');
       // 2. 获取表单数据
       $userName = 'test1';
       $oldPswd = '111';
       $newPswd = '222';
       // 3. 注册
       $r = $userTable->doChangePswd($userName, $oldPswd, $newPswd);
       dump($r);
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