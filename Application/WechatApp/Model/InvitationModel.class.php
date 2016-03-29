<?php
namespace WechatApp\Model;
use Think\Model;
use Org\Util\String;
use Org\Util\Date;
use \Think\Page;
/**
 * 邀请码
 * @author Pluto Xu
 *
 */
class InvitationModel extends Model {
    // 当前数据表
    protected $trueTableName = "invitation";

    // 字段限定信息
//     protected $_validate = array(
//         // array('username', 'require'),
//         array('password', 'require'),
//         array('c', '','帐号名称已经存在！',0,'unique',1),
//     );

    ////////////////////////////////////////////////////
    /**
     * 获取邀请码状态
     * @param unknown $invitationCode
     * @return mix    若邀请码存在，返回邀请码状态，不存在返回false
     */
    public function getStatus($invitationCode){
        if(empty($invitationCode)){
            return false;
        }
        
       $code = $this->where(array('invitationCode' => $invitationCode))->select();
       \Think\Log::write(dump($code,false),'WARN');
       if(empty($code)){
           return false;
       }else{
           return $code[0]['status'];
       }
    }

    
    
    /**
     * 实现邀请码添加操作
     * @param  string $invitationCode  邀请
     * @param  string $validDate  有效期
     * @return mix  若添加成功，返回该记录的主键id；否则（失败）返回false
     */
    public function doInvitationCodeAdd($invitationCode,$validDate) {
        // // 1. 数据校验
        if (empty($invitationCode) || empty($validDate)) {
            return false;
        }
        // // 2. 判断邀请码是否存在（若邀请码已经存在，不能再添加该邀请码）
        if ($this->isInvitationCodeExists($invitationCode)) {
            return false;
        }
        // 3. 实现添加
        $data['invitationCode'] = $invitationCode;
        $data['validDate'] = $validDate;
        $data['status'] = 0;
        return $this->data($data)->filter('strip_tags')->add();
    }

    /**
     * 修改状态操作
     * @param  string $invitationCode 邀请码
     * @param  string $status  新的状态
     * @return boolean           若修改成功，返回true；否则返回false
     */
    public function doChangeStatus($invitationCode, $status) {
        // 1. 数据字段校验
      
        // 4. 修改状态
        $data['status'] = $status;
        $date['useDate'] = time();
        return $this->where(array('invitationCode' => $invitationCode))->save($data);
    }
    
    /**
     * 修改留言
     * @param  [type] $where 查询条件（哪些记录符合条件）
     * @param  array  $data  修改后的记录值
     * @return [type]        [description]
     */
    public function doChange($where, $data = array()){
        // 1. 查询条件的判断
        if (empty($where)) {
            return false;
        }
//         dump($this->fields);
        // 2. 修改
        return $this->where($where)->save($data);
    }

    /**
     * 返回所有记录
     * @return mix 记录合集
     */
    public function getAllRecords(){
        
        return $this->select();
    }
    
    /**
     * 判断指定邀请码是否存在
     * @param  string  $invitationCode 待判断的用户名
     * @return boolean           若邀请码存在，返回true；否则返回false
     */
    public function isInvitationCodeExists($invitationCode) {
        // 1. 判断用户名是否有效
        if (empty($invitationCode)) {
            return false;
        }
        // 2. 查询指定条件的记录个数
        $count = $this->where(array(
            'invitationCode'  =>  $invitationCode
        ))->count();

        // 返回
        return 1 == $count;
    }

    /**
     * 验证指定邀请码是否有效，验证通过后锁定邀请码。
     * @param  string  $invitationCode 邀请码
     * @return boolean           若邀请有效，则返回true；否则返回false
     */
    public function doInvitationCodeCheck($invitationCode) {
        $code = $this->where(array(
            'invitationCode'  =>  $invitationCode
        ));
        $count = $code->count();
        $data = $code->select();
        if(1===$count){
            if (0===$data['status']){
                return self::doChangeStatus($invitationCode,10);  //设置邀请码状态为已启用
            }else{
                return false;
            }
            
        }else{
            return false;
        }
    }
    
    /**
     * 生成随机的邀请码到数据库。
     * @return mix           若生成成功返回生成的邀请码，否则返回false
     */
    public function generateInvitationCode(){
        $code = String::randString(32);
        while(self::isInvitationCodeExists($code)){  //如果已存在，重新生成。
            $code = String::randString(32);
        }
        $Date = new Date();
        
        $validDate = $Date->dateAdd(1,'m');
        //生成到数据库中
        $id = self::doInvitationCodeAdd($code,$validDate);
        if($id>0){
            return $code;
        }else{
            return false;
        }
        
    }
    
    /**
     * 获取当前页
     * @return multitype:unknown NULL Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object> 
     */
    public function getPage(){
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
                $msgs = $this->limit($page->firstRow . ',' . $page->listRows)->select();
//         $msgs =$this->join('__USERS__ on __USERS__.id=__MSGS__.userid','LEFT')->limit($page->firstRow . ',' . $page->listRows)->select();
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