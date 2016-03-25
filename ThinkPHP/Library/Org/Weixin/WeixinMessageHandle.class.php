<?php
namespace Org\Weixin;
// import \Org\Weixin\Bean\WeixinOutMessage;
// import \Org\Weixin\Bean\WeixinTextOutMessage;

// import \Org\Weixin\Bean\WeixinInMessage;

/**
 * 微信消息处理类
 * @author Pluto Xu
 *
 */
class WeixinMessageHandle
{


    
    /**
     * 消息处理
     * @param unknown $InMessage
     */
    static public function messageAction($InMessage){
        if(!empty($InMessage)){
            \Think\Log::write('=========消息处理开始==========','DEBUG');
            $OutMessage = self::messageIn($InMessage);
            if(!empty($OutMessage)){
                self::messageOut($OutMessage);
            }
            \Think\Log::write('=========消息处理结束==========','DEBUG');
                
        }
        
    }
    

    /**
     * 消息接收
     * @param unknown $postObj
     */
    static private function messageIn($InMessage){
        $MsgType =!empty($InMessage->MsgType)?$InMessage->MsgType:'unknow';
        $FromUsername = !empty($InMessage->FromUserName)?$InMessage->FromUserName:'testUserFrom';
        $ToUsername = !empty($InMessage->ToUserName)?$InMessage->ToUserName:"testUserTo";
        \Think\Log::write('=========消息解析开始==========','DEBUG');
        $OutMessage = new \Org\Weixin\Bean\WeixinOutMessage($FromUsername,$ToUsername);
        switch ($MsgType){
            case 'text':  //文本消息
                \Think\Log::write('=========文本消息==========','DEBUG');
                $OutMessage = self::textMessageAction($InMessage,$OutMessage);
                break;
            case 'event': //事件消息
                \Think\Log::write('=========事件消息==========','DEBUG');
                $OutMessage = self::eventMessageAction($InMessage,$OutMessage);
                break;
        default: //不支持的消息类型
            $OutMessage = self::getTextMessageOut($OutMessage,'小白正在努力升级。。。');
            break;
        }
        \Think\Log::write('=========消息解析结束==========','DEBUG');
        return $OutMessage;
     
    }
    
    /**
     * 消息返回
     * @param unknown $OutMessage
     */
    static private function messageOut(\Org\Weixin\Bean\WeixinOutMessage $OutMessage){
        \Think\Log::write('=========消息返回开始==========','DEBUG');
        \Think\Log::write('=========返回的消息内容:'.$OutMessage->format().'==========','DEBUG');
        echo $OutMessage->format();
        \Think\Log::write('=========消息返回结束==========','DEBUG');
    }

    
    
    /**
     * 文本消息的处理
     * @param unknown $InMessage
     */
    static private function textMessageAction($InMessage,\Org\Weixin\Bean\WeixinOutMessage $OutMessage){
        \Think\Log::write('=========消息内容:'.$InMessage->Content.'=========','DEBUG');
        \Think\Log::write('=========消息来源:'.$OutMessage->__get('FromUserName').'=========','DEBUG');
        
        $TulingData= \Org\Weixin\WeixinTuling::getTulingMessage($InMessage->Content,$OutMessage->__get('FromUserName'));
        $TulingData=\Org\Tuling\TulingUtil::formatMsg($TulingData);
        $OutMessage=self::getTextMessageOut($OutMessage,$TulingData);

        return $OutMessage;
    }
    
    /**
     * 事件消息的处理
     * @param unknown $InMessage
     * @param \Org\Weixin\Bean\WeixinOutMessage $OutMessage
     * @return \Org\Weixin\Bean\WeixinTextOutMessage
     */
    static private function eventMessageAction($InMessage,\Org\Weixin\Bean\WeixinOutMessage $OutMessage){
        $EventType = !empty($InMessage->Event)?$InMessage->Event:'unknow';
        //print_r($InMessage);
        \Think\Log::write('=========事件类型:'.$EventType.'=========','DEBUG');
        switch ($EventType){
            case 'subscribe': //关注事件
                $OutMessage = self::getTextMessageOut($OutMessage,'感谢你关注飞一站的个人公众号，我是小白●▽●。有事没事尽管调戏，不要跟我客气，你客气就是对我的最大的不客气！');
                break;
            case 'unsubscribe': //取消关注事件
                $OutMessage = self::getTextMessageOut($OutMessage,'主人，你不要小白啦！●︿●');
                break;
            default:
                $OutMessage = self::getTextMessageOut($OutMessage,'你说什么？我听不懂呀！');
                break;
        }
        
        return $OutMessage;
    }


    /**
     * 获取文本消息类型
     * @param \Org\Weixin\Bean\WeixinOutMessage $OutMessage
     * @param unknown $Content
     * @return \Org\Weixin\Bean\WeixinTextOutMessage
     */
    static private function getTextMessageOut(\Org\Weixin\Bean\WeixinOutMessage $OutMessage,$Content){
        $OutMessage->__set('MsgType', 'text');
        $OutMessage->__set('Content', $Content);
        return $OutMessage;
    }
    
    
}

?>