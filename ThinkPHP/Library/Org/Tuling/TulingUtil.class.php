<?php
namespace Org\Tuling;

/**
 * 图灵接口数据转换工具
 * @author Pluto Xu
 *
 */
class TulingUtil
{
    /**
     *  最大文本内容大小 600
     * @var unknown
     */
    static $MAX_TEXT_SIZE = 600;
    
    /**
     * 格式化图灵消息对象
     * @param $data
     * @return string
     */
    public static function formatMsg($data){
        $msg='';
        $tulingData = new TulingResponseData();
        if(!empty($data)){
            switch($data->code){
                case $tulingData::$CODE_TEXT: //文本
                    $msg.=substr($data->text,0,self::$MAX_TEXT_SIZE);
                    break;
                case $tulingData::$CODE_URL:  //URL
                    $msg.=$data->text;
                    $msg.="\n";
                    $msg.=$data->url;
                    if(strlen($msg)>=512){
                        $msg='内容太长了，小白无法消化！';
                    }
                    break;
                case $tulingData::$CODE_NEWS: //新闻

                    if(!empty($data->list)){
                        
                        $msg.=$data->text;
                        $msg.="\n";
                        foreach ($data->list as $list){
                            if(strlen($msg)>=self::$MAX_TEXT_SIZE){
                               break;
                            }
                            $msg.=$list->source;
                            $msg.=':';
                            $msg.=$list->article;
                            $msg.="\n";
                            $msg.=$list->detailurl;
                            $msg.="\n";
                            $msg.="\n";
                        }
                    }else{
                        $msg.='你没洗手，所以啥都木有。';
                    }
                    break;
                case $tulingData::$CODE_MENU: //菜谱
                 
                    if(!empty($data->list)){
                        $msg.=$data->text;
                        $msg.="\n";
                        foreach ($data->list as $list) {
                            if(strlen($msg)>=self::$MAX_TEXT_SIZE){
                              break;
                            }
                            $msg .= '菜谱：';
                            $msg .= $list->name;
                            $msg .= "\n";
                            $msg .= $list->info;
                            $msg .= "\n";
                            $msg .= "详情";
                            $msg .= ':';
                            $msg .= $list->detailurl;
                            $msg .= "\n";
                            $msg .= "\n";
                        }
                    }else{
                        $msg.='你没洗手，所以啥都木有。';
                    }
                    break;
                default:
                
                    $msg.='哈哈，服务器抽风了，要不你再试试？';
                    break;
            }
	
        }else{
            $msg.='糟糕！！！机器人没电了，谁去帮忙充下电呀！！！';
        }

        return $msg;
    }
    
}

?>