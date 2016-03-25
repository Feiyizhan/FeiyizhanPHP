<?php
namespace Org\Weixin\Bean;

/**
 * 微信返回消息
 * @author Pluto Xu
 *
 */
class WeixinOutMessage
{

    static $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>";
    
    private $ToUserName;
    
    private $FromUserName;
    
//     private $CreateTime;
    
    private $MsgType ;
    
    // 文本消息
    private $Content;

    /**
     * 构造函数
     * @param unknown $ToUserName
     * @param unknown $FromUserName
     */
    public function __construct($ToUserName,$FromUserName) {
         $this->ToUserName = $ToUserName;
         $this->FromUserName =$FromUserName;
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
    
    /**
     * 格式化消息返回
     * @return string
     */
    public function format(){

        switch ($this->MsgType){
            case 'text':
                return sprintf(self::$textTpl,
                    $this->ToUserName,
                    $this->FromUserName,
                    time(),
                    $this->MsgType,
                    $this->Content
                );
                break;
            defaut:
                return '';
                break;
        }
        
    }
}

?>