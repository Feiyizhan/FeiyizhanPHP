<?php

namespace Mmsgs\Model;
use Think\Model;

class UsersModel extends Model {
    // 当前数据表
    protected $trueTableName = "users";

    // 字段限定信息
    protected $_validate = array(
        // array('username', 'require'),
        array('password', 'require'),
        array('username', '','帐号名称已经存在！',0,'unique',1),
    );
    
    ////////////////////////////////////////////////////
    
    public function getUserIdByUserName($userName) {
        $cond['username'] = $userName;
        $r = $this->where($cond)->getField('id');
        return $r;
    }
    
    /**
     * 实现用户注册
     * @param  string $userName  用户名
     * @param  string $userPswd  用户密码
     * @param  string $userImage 用户头像的地址
     * @return mix            若用户注册成功，返回该记录的主键id；否则（失败）返回false
     */
    public function doUserRegister($userName, $userPswd, $userImage) {
        // // 1. 数据校验
        // if (empty($userName) || empty($userPswd)) {
        //     return false;
        // }
        if (empty($userImage)) {
            $userImage = '1.jpg';
        }
        // // 2. 判断用户是否存在（若用户已经存在，不能再注册该用户名）
        // if ($this->isUserExists($userName)) {
        //     return false;
        // }
        // 3. 实现注册操作
        $data['username'] = $userName;
        $data['password'] = $userPswd;
        $data['image'] = $userImage;
        // return $this->add($data);
        if ($this->create($data)) {   // 使用字段数据验证的话，必须显式地调用create()方法
            return $this->add();
        } 
        echo $this->getError();
        return false;
    }

    /**
     * 修改密码操作
     * @param  string $userName 用户名
     * @param  string $oldPswd  原始密码
     * @param  string $newPswd  新密码
     * @return boolean           若修改成功，返回true；否则返回false
     */
    public function doChangePswd($userName, $oldPswd, $newPswd) {
        // 1. 数据字段校验
        // 
        // 2. 判断原始密码和初始密码是否一致
        if ($oldPswd === $newPswd) {
            return false;
        }
        // 3. 验证原始密码
        if (!$this->isValidUser($userName, $oldPswd)) { // 当前不是有效用户，不能修改密码
            return false;
        }
        // 4. 修改密码
        $data['username'] = $userName;
        $data['password'] = $newPswd;
        return $this->where(array('username' => $userName))->save($data);
    }

    /**
     * 判断指定用户名的用户是否存在
     * @param  string  $userName 待判断的用户名
     * @return boolean           若用户名存在，返回true；否则返回false
     */
    public function isUserExists($userName) {
        // 1. 判断用户名是否有效
        if (empty($userName)) {
            return false;
        }
        // 2. 查询指定条件的记录个数
        $count = $this->where(array(
            'username'  =>  $userName
        ))->count();

        // 返回
        return 1 === $count;
    }

    /**
     * 判断指定用户名和密码的用户是否为有效用户
     * @param  string  $userName 用户名
     * @param  string  $userPswd 用户密码
     * @return boolean           若用户名和密码有效，则返回true；否则返回false
     */
    public function isValidUser($userName, $userPswd) {
        $count = $this->where(array(
            'username'  =>  $userName,
            'password'  =>  $userPswd,
        ))->count();
        return false !== $count;
    }
}