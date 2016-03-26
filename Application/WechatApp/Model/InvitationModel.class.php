<?php
namespace WechatApp\Model;
use Think\Model;
use Org\Util\String;
use Org\Util\Date;
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
       if(empty($code)){
           return false;
       }else{
           return $code['status'];
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
     * 生成随机的验证码到数据库。
     * @return mix           若生成成功返回生成的验证码，否则返回false
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
}