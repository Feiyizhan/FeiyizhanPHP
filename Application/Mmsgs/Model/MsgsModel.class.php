<?php

namespace Mmsgs\Model;
use Think\Model;
use \Think\Page;

class MsgsModel extends Model {

    protected $trueTableName = 'msgs';  // 当前模型类对应的数据表名称

    /**
     * 通过用户id获取该用户所发表的留言
     * @param  [type] $userid [description]
     * @return [type]         [description]
     */
    public function getMsgsByUserId($userid) {
        return $this->join('__USERS__ on __USERS__.id=__MSGS__.userid','LEFT')->where('userid = ' . $userid)->select();
    }

    /**
     * [addMsg description]
     * @param [type] $msgTitle  [description]
     * @param string $msgBody   [description]
     * @param [type] $msgUserId [description]
     */
    public function addMsg($msgTitle, $msgBody = "", $msgUserId = null) {
        $data = array();
        // 1. 先判断$msgTitle类型
        if (is_array($msgTitle)) {
            $data['title'] = $msgTitle['title'];
            $data['body'] = $msgTitle['body'];
            $data['userid'] = $msgTitle['userid'];
        } else if (is_string($msgTitle)) {
            $data['title'] = $msgTitle;
            if (empty($msgBody) || !msgUserId) {
                return false;
            }
            $data['body'] = $msgBody;
            $data['userid'] = $msgUserId;
        }

        // 2. 数据库插入
        return $this->add($data);
    }

    /**
     * 修改留言
     * @param  [type] $where 查询条件（哪些记录符合条件）
     * @param  array  $data  修改后的记录值
     * @return [type]        [description]
     */
    public function updateMsg($where, $data = array()) {
        // 1. 查询条件的判断
        if (empty($where)) {
            return false;
        }

        // 2. 修改
        return $this->where($where)->save($data);
    }

    public function deleteMsg($where) {
        // 1. 条件判断
        if (!$where || empty($where)) {
            return false;
        }

        // 2. 开始删除
        // 2.1 先获取当前留言的主键
        $id = $this->where($where)->getField('id');
        // dump($id);exit;
        // 2.2 删除当前留言的回帖信息
        $rmsgsTable = D('rmsgs');
        if (false !== $rmsgsTable->deleteRmsgsByMsgId($id)) {
            // 2.3 删除当前留言
            return $this->where($where)->delete();
        }
        return false;
    }

    public function getMsgById($id) {
        // 1. 先获取主贴
        $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
//         $msg = $this->getByID($id);
        $msg=$Model->query('select m.*,u.username,u.image from msgs m LEFT JOIN users u on u.id=m.userid where m.id='.$id);
        trace($Model->query('select m.*,u.username,u.image from msgs m LEFT JOIN users u on u.id=m.userid where m.id='.$id),'SQL','debug');
        // 2. 获取回帖
        trace($id,'DATA','debug');
        $rmsgsTable = D('rmsgs');
        $msg['rmsgs'] = $rmsgsTable->getRmsgsByMsgId($id);
        trace($msg,'DATA','debug');
        // 3. 返回
        return $msg;
    }

    public function getMsgsByPage() {
        // 1. 得到数据集的总数
        $count = $this->count();
        // 2. 创建分页类(Page)
        $page = new Page($count, C('page_rows'));
        // 3.0 设置分页链接信息
        $page->setConfig('theme', "%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%");
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        // 3. 获取分页码
        $show = $page->show();
        // 4. 获取分页记录
//         $msgs = $this->limit($page->firstRow . ',' . $page->listRows)->select();
        $msgs =$this->join('__USERS__ on __USERS__.id=__MSGS__.userid','LEFT')->limit($page->firstRow . ',' . $page->listRows)->select();
//         $users = D('users');
//         trace($this->join('__USERS__ on __USERS__.id=__MSGS__.userid','LEFT')->limit($page->firstRow . ',' . $page->listRows)->buildSql(),'用户信息','debug');
        // 5. 生成返回结果
        $results = array();
        $results['lists'] = $msgs;
        $results['pages'] = $show;
        $results['pageCount'] = $page->totalPages;
        // 6. 返回
        return $results;
    }

}