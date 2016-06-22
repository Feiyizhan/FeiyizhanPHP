<?php
return array(
	//'配置项'=>'配置值'
    'DEFAULT_CONTROLLER'    =>  'WeixinServlet',    // 设置默认Controller
    'LOG_LEVEL'  =>'DEBUG,INFO,EMERG,ALERT,CRIT,ERR',
    /* 分页 */
//     'page_rows'  =>  10,
//     'VAR_PAGE'   =>  'page',
    
    // 布局设置
    'TMPL_ENGINE_TYPE'      =>  'Think',     // 默认模板引擎 以下设置仅对使用Think模板引擎有效
    'TMPL_CACHFILE_SUFFIX'  =>  '.php',      // 默认模板缓存后缀
    'TMPL_DENY_FUNC_LIST'   =>  'echo,exit',    // 模板引擎禁用函数
    'TMPL_DENY_PHP'         =>  false, // 默认模板引擎是否禁用PHP原生代码
    'TMPL_L_DELIM'          =>  '{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '}',            // 模板引擎普通标签结束标记
    'TMPL_VAR_IDENTIFY'     =>  'array',     // 模板变量识别。留空自动判断,参数为'obj'则表示对象
    'TMPL_STRIP_SPACE'      =>  true,       // 是否去除模板文件里面的html空格与换行
    'TMPL_CACHE_ON'         =>  true,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_PREFIX'     =>  '',         // 模板缓存前缀标识，可以动态改变
    'TMPL_CACHE_TIME'       =>  0,         // 模板缓存有效期 0 为永久，(以数字为值，单位:秒)
    'TMPL_LAYOUT_ITEM'      =>  '{__CONTENT__}', // 布局模板的内容替换标识
    'LAYOUT_ON'             =>  false, // 是否启用布局
    'LAYOUT_NAME'           =>  'layout', // 当前布局名称 默认为layout
    
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'wexinServlet',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'q1w2e3r4!',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '',    // 数据库表前缀
    'DB_PARAMS'             =>  array(), // 数据库连接参数
    'DB_DEBUG'              =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  false,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
    
    
    /* 数据缓存设置 */
    'DATA_CACHE_TIME'       =>  0,      // 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_COMPRESS'   =>  false,   // 数据缓存是否压缩缓存
    'DATA_CACHE_CHECK'      =>  false,   // 数据缓存是否校验缓存
    'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
    'DATA_CACHE_TYPE'       =>  'File',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PATH'       =>  TEMP_PATH,// 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_KEY'        =>  '', // 缓存文件KEY (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR'     =>  false,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       =>  1,        // 子目录缓存级别
    
    /* 微信公共配置*/
//     'WECHAT_CONFIG_TOKEN'   =>  
//     'WECHAT_CONFIG_AESKEY'  =>  
//     'WECHAT_CONFIG_APPID'   =>  
//     'WECHAT_CONFIG_APPSECRET' => 
    
//     'WECHAT_SDK'            =>  array(    //SDK Option
//         'token'             =>  '7rvtxyfeu476wtgzsvtjx3asnvleqryk',  // Token(令牌)
//         'encodingaeskey'    =>  'haspjfcjlOtkmfoNk1LxWV4K3mRJiEVq4dw4vEpl2QZ', //EncodingAESKey(消息加解密密钥)
//         'appid'             =>  'wxf927f6d004426416', //'wx5b36f13d4810323c',  //AppID(应用ID)
//         'appsecret'         =>  'd4624c36b6795d1d99dcf0547af5443d',//'1bb0ea4b50d53d3870d724cc7fe8fda3', //AppSecret(应用密钥)
//         'debug'             =>  true,
//         'logcallback'       =>  ''
        
//     ),
    
    /* redis 配置 */
    'REDIS_HOST'            =>  '127.0.0.1',
    'REDIS_PORT'            =>  '6379',
    'REDIS_PASSWORD'        =>  'q1w2e3r4', 
    
    /* QQSDK 配置 */
    'QQSDK'                 => array(
        'appid'             => '101311992',
        'appkey'            => 'b5a4be14ceaf8270c64533003aeec3c8',
        'callback'          => 'http://wxgzh.woxiang212.cn/Admin/QQCallBack.html',
        'scope'             => 'get_user_info',
        'errorReport'       => true,
        'storageType'       => 'file',
        
        
    )
);