<?php
namespace WeixinServlet\Controller;
use Think\Controller;
class WeixinServletController extends Controller {
    public function index(){
        if(IS_GET){
            //print_r(I('get.'));
            $this->valid();
        }elseif(IS_POST){
            $this->responseMsg();

        }
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    private function valid(){
        //print_r( I('get.'));
        \Think\Log::write('测试日志信息===================','WARN');
        
        
        $signature = I('get.signature/s');
        $timestamp=I('get.timestamp/s');
        $nonce =I('get.nonce/s');
        
        \Think\Log::write($signature,'WARN');
        
        
        $token = '7rvtxyfeu476wtgzsvtjx3asnvleqryk';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr );
        $tmpStr = sha1($tmpStr );
        
        if( $tmpStr == $signature ){
            $echoStr=I('get.echostr/s');
            echo $echoStr;
            \Think\Log::write('验证通过','WARN');
            //return true;
        }else{
            //return false;
            \Think\Log::write('验证失败','DEBUG');
        }
        
        \Think\Log::write('测试日志信息===================','DEBUG');
    }
    
    private function responseMsg(){
        \Think\Log::write('测试日志信息===================','DEBUG');
        
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        \Think\Log::write('=========请求内容:'.$postStr.'=========','DEBUG');
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            \Think\Log::write('=======调用消息处理引擎===============','WARN');
            \Org\Weixin\WeixinMessageHandle::messageAction($postObj);
            \Think\Log::write('测试日志信息===================','WARN');
            
        }
    }
}