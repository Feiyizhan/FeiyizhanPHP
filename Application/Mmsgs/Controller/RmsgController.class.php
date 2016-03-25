<?php
namespace Mmsgs\Controller;
use Think\Controller;

class RmsgController extends Controller {
    public function recipemsg() {
        // 0. 获取当前主留言的id
        $msgid = I('get.msgid');
        if (IS_POST) {
            // 1. 实例化数据表操作对象
            $rmsgsTable = D('rmsgs');
            // 2. 获取表单数据
            $content = I('post.content');
            // 3. 添加留言
            $userid = session('loginedUserId');
            $r = $rmsgsTable->recipeMsg($msgid, $userid, $content);
            // 4. 添加留言后的处理
            if (false !== $r) {
                $this->success('回复留言成功！', '../../../../msg/viewmsg/msgid/' . $msgid);
            } else {
                $this->error('添加回复失败！');
            }
        } else {
            if (session('?loginedUser')) {
                // 获取当前主留言的详细信息
                $msgsTable = D('msgs');
                $msgObj = $msgsTable->getMsgById($msgid);
                // 显示视图
                $this->assign('msg', $msgObj);
                $this->assign('view_title', '发表留言');
                $this->display();
            } else {
                // 提示用户登录，不能添加留言
                $this->error('只有登录用户才可以发表回复，请您先登录！', '../../user/login/');
            }
        }
    }

    public function editrmsg() {

    }

    public function deletermsg() {
        $rmsgsTable = D('rmsgs');
        $rmsg = $rmsgsTable->getRmsgByRmsgid(I('get.rmsgid'));
        // dump($rmsg);exit;        
        $r = $rmsgsTable->deleteRmsg('id=' . I('get.rmsgid'));
        if (false !== $r) { // 删除成功
            $this->success('回帖删除成功！', '__MODULE__/msg/viewmsg/msgid/' . $rmsg['msgid']);
        } else {
            $this->error('回帖删除失败！');
        }
    }
}