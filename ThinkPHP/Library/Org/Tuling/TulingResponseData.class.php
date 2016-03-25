<?php
namespace Org\Tuling;

/**
 * 图灵接口返回数据格式
 * @author Pluto Xu
 *
 */
class TulingResponseData
{
    /**
     * 100000-Text OK.
     * 200000 - URL OK.
     * 302000 - news OK.
     * 305000 - 列车 OK  -- 新版本取消了该类型
     * 306000 - 航班 OK. -- 新版本取消了该类型
     * 308000 - 菜谱 OK.
     * ********** - 服务器正在升级
     * 40001 - 参数key长度错误（应该是32位）
     * 40002 - 请求内容info为空
     * 40003 - key错误或帐号未激活
     * 40004 - 当天请求次数已使用完
     * 40005 - 暂不支持所请求的功能
     * 40006 - 图灵机器人服务器正在升级
     * 40007 - 数据格式异常
     *
     */
    public static $CODE_URL ="200000";
    public static $CODE_TEXT ="100000";
    public static $CODE_NEWS ="302000";
    public static $CODE_TRAIN ="305000";
    public static $CODE_FLIGHT ="306000";
    public static $CODE_MENU ="308000";
    
    private $code;
    private $text;
    private $url;
    private $list;
    
    /*
     * train
     */
    
    private $trainnum;
    private $start;
    private $terminal;
    private $starttime;
    private $endtime;
    private $icon;
    private $detailurl;
    
    /*
     * news
     */
    private $article;
    private $source;
    //private $icon;
    //private $detailurl;
    
    /*
     * menu
     */
    private $name;
//     private $icon;
    private $info;
//     private $detailurl;

    /*
     * flight
     */
    private $flight;
//     private $starttime;
//     private $endtime;
//     private $icon;

    /**
     * @param unknown $name
     * @param unknown $value
     */
    public function __set($name,$value){
        $this->$name = $value;
    }
    
    /**
     * @param unknown $name
     */
    public function __get($name){
        return $this->$name;
    }
    
}

?>