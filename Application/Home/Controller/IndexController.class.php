<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
//         $data = \Org\Weixin\WeixinTuling::getTulingMessage('关注', 'feiyizhan');
        //$data=\Org\Tuling\TulingUtil::transferObj($data);
//         $this->show('<br/>'.print_r($data));
//         $data =\Org\Tuling\TulingUtil::formatMsg($data);
//         $this->show('<br/>'.print_r($data));
//         $json='{"code":302000,"text":"亲，已帮您找到相关新闻","list":[{"article":"人社部举例说明渐进式延退:或1年延3个月","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/02/BH1PK7N800014Q4P.html"},{"article":"沈阳规定毕业未满5年大学生购房零首付","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/16/BH39PAGD00014JB5.html"},{"article":"剑南春董事长失联数月 或因改制股权纷争","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/21/BH3OO8HU00014AED.html"},{"article":"快来找出不是范伟的那个人!","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/14/BH328EC800014TUH.html#163interesting?xstt"},{"article":"这些可移动小木屋你想买吗？","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/18/BH3G5GHL00014TUH.html#163interesting?xstt"},{"article":"在她手里,旧书也能变艺术品","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/18/BH3FOQHO00014TUH.html#163interesting?xstt"},{"article":"网友给织的毛线窝,暖暖哒!","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/18/BH3F08D400014TUH.html#163interesting?xstt"},{"article":"这6种梦,哪个最让你惊悚?","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/17/BH3C2ECT00014TUH.html#163interesting?xstt"},{"article":"麦当劳可口可乐推出VR头盔","source":"网易新闻","icon":"","detailurl":"http://news.163.com/16/0301/17/BH3BP2SP00014U9R.html#163interesting?xstt"}]}';
//         $tulingData =json_decode($json);
//         $this->show(print_r($tulingData));
//         $this->show('<br/>'.print_r($tulingData->list[0]->article));
//         foreach ($tulingData->list as $list){
//             $this->show('<br/>'.print_r($list->article));
//         }
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
           $this->show('欢迎来到飞一站的主页');
        
    
    }

    public function testWeixin() {
        $req = ' <xml>    <ToUserName><![CDATA[1266ssdfd]]></ToUserName>    <FromUserName><![CDATA[feiyizhan]]></FromUserName>    <CreateTime>1456853968</CreateTime>    <MsgType><![CDATA[event]]></MsgType>    <Event><![CDATA[subscribe]]></Event>    <EventKey><![CDATA[]]></EventKey>    <MsgId>1234567890abcdef</MsgId></xml>';
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($req, 'SimpleXMLElement', LIBXML_NOCDATA);
        
        \Think\Log::write('=======调用消息处理引擎===============', 'WARN');
        \Org\Weixin\WeixinMessageHandle::messageAction($postObj);
    }
}