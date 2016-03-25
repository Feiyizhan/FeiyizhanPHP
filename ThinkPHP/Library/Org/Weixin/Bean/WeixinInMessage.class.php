<?php
namespace Org\Weixin\Bean;

class WeixinInMessage
{

    
    /**************** 基础信息********************/
    
    private $ToUserName;// 开发者微信号
    
    private $FromUserName;// 发送方帐号（一个OpenID)
    
    private $CreateTime;// 消息创建时间 （整型）
    
    /**
     * text： 文本
     * image： 图片
     * voice：语音
     * video：视频；
     * location：地理位置
     * link：链接消息
     * event： 事件
     * @var unknown
     */
    private $MsgType;
    
    private $MsgId;// 消息id，64位整型
    
    /****************  文本消息  ********************/
    private $Content;
    
    /****************  图片消息  ********************/
    private $PicUrl;
    
    /****************  位置消息  ********************/
    private $LocationX;
    
    private $LocationY;
    
    private $Scale;
    
    private $Label;
    
    /****************  链接消息  ********************/
    private $Title;
    
    private $Description;
    
    private $Url;
    
    /****************  语音信息  ********************/
    
    private $MediaId;
    
    private $Format;
    
    private $Recognition;
    
    /****************  视频信息  ********************/
    
    private $ThumbMediaId;
    
    /****************  事件  ********************/
    /**
     * 事件类型：
     * subscribe：订阅
     * unsubscribe：取消订阅
     * subscribe：用户未关注时，进行关注后的事件推送
     * scan：用户已关注时的事件推送
     * LOCATION：上报地理位置事件
     * CLICK：自定义菜单事件
     * */
    private $Event;//
    
    /**
     * a：未关注：事件KEY值，qrscene_为前缀，后面为二维码的参数值
     * b：已关注：事件KEY值，是一个32位无符号整数
     * c：自定义菜单：事件KEY值，与自定义菜单接口中KEY值对应
     * */
    private $EventKey;//
    
    private $Ticket;// 二维码的ticket，可用来换取二维码图片
    
    /****************  上报地理位置事件  ********************/
    private $Latitude;// 地理位置纬度
    
    private $Longitude;// 地理位置经度
    
    private $Precision;// 地理位置精度
    
    /**
     * 构造函数
     */
    public function __construct() {
        
    }
    
    /**
     * 析构函数，用于在页面执行结束后自动关闭打开的文件。
     *
     */
    public function __destruct() {
        
    }
    
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