<?php
namespace Mmsgs\Controller;
use Think\Controller;

class MsgController extends Controller {
    public function addmsg() {
        if (IS_POST) {
            // 1. 实例化数据表操作对象
            $msgsTable = D('msgs');
            // 2. 获取表单数据
            $data['title'] = I('post.title');
            $data['content'] = I('post.content');
            // 3. 添加留言
            $data['userid'] = session('loginedUserId');
            $r = $msgsTable->addMsg($data['title'], $data['content'], $data['userid']);
            // 4. 添加留言后的处理
            if (false !== $r) {
                $this->success('添加留言成功！', '__MODULE__/msg/index/');
            } else {
                $this->error('添加留言失败！');
            }
        } else {
            if (session('?loginedUser')) {
                // 显示视图
                $this->assign('view_title', '发表留言');
                $this->display();
            } else {
                // 提示用户登录，不能添加留言
                $this->error('只有登录用户才可以发表留言，请您先登录！', '__MODULE__/user/login/');
            }
        }
    }

    public function editmsg() {
        // 1. 实例化数据表操作对象
        $msgsTable = D('msgs');
        if (IS_POST) {
            // 0. 获取当前的留言id
            $msgId = I('get.msgid');
            // 2. 获取表单数据
            $data['title'] = I('post.title');
            $data['body'] = I('post.content');
            // 3. 添加留言
            // $data['userid'] = session('loginedUserId');
            $r = $msgsTable->updateMsg('id='.$msgId, $data);
            // 4. 添加留言后的处理
            if (false !== $r) {
                $this->success('修改留言成功！', '__MODULE__/msg/index/');
            } else {
                $this->error('修改留言失败！');
            }
        } else {
            // 1. 获取修改的是哪一条留言
            $msgId = I('get.msgid');
            // 2. 获取当前留言的基本信息
            $msgObj = $msgsTable->getMsgById($msgId);
            // 3. 是否为当前用户发表的留言
            if (session('?loginedUser') && session('loginedUserId') == $msgObj['userid']) {
                // 显示视图
                $this->assign('msg', $msgObj);
                $this->assign('view_title', '修改留言');
                $this->display();
            } else {
                // 提示用户登录，不能添加留言
                $this->error('只有留言的发表者才可以修改该留言！', '__MODULE__/msg/index/');
            }
        }
    }

    public function deletemsg() {
        $msgsTable = D('msgs');
        // $r = $msgsTable->deleteMsg('id = 6');
        // dump($r);
        $r = $msgsTable->deleteMsg('id=' . I('get.msgid'));
        if (false !== $r) { // 删除成功
            $this->success('留言删除成功！', '__MODULE__/msg/index/');
        } else {
            $this->error('留言删除失败！');
        }
    }

    public function index() {
        // 实例化数据库操作类
        $msgsTable = D('msgs');
        // 获取分页记录
        $r = $msgsTable->getMsgsByPage();
//         dump($r);
        // 分页记录和分页信息赋值给视图文件
        $this->assign('lists', $r['lists']);
        $this->assign('pages', $r['pages']);
        // 指定视图标题s
        $this->assign('view_title', '首页');
//         echo ("test");
        // 显示视图
        $this->display();
    }

    public function viewmsg() {
        // 1. 创建数据表操作对象
        $msgTable = D('msgs');
        // 2. 获取指定url参数
        $msgId = I('get.msgid');
        if (!$msgId || empty($msgId)) {
            $msgId = 1;
        }
        // 3. 获取记录
        $msg = $msgTable->getMsgById($msgId);
        // 4. 为视图赋值
        $this->assign('msg', $msg);
        // 5. 显示视图文件
        $this->assign('view_title', $msg['title']);
        $this->display();
    }
}