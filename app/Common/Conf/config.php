<?php
return array(
    'MULTI_MODULE' => false,
    'DEFAULT_MODULE' => 'Home',
    
    /* 数据库设�? */
    'DB_TYPE'               =>  'mysql',     // 数据库类�?
    'DB_HOST'               =>  'localhost', // 服务器地�?
    'DB_NAME'               =>  'jh100',          // 数据库名
    'DB_USER'               =>  'root',      // 用户�?
    'DB_PWD'                =>  'huw$e!i@shen22255555',          // 密码
    'DB_PREFIX' => 'tc_', // 数据库表前缀 
    'DB_CHARSET'=> 'utf8', // 字符�?
    

    'DATA_AUTH_KEY' => 'tke")*Bs6]2<Sg8Ca3Y>5dFq@,znLH4KI%?R=.~h',
    'USER_ADMINISTRATOR' => 1, //管理员用户ID
    
    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小�? true则表示不区分大小�?
    'URL_MODEL'            => 2, //URL模式
    
	'LOG_RECORD' => false,
	
    
	//缓存设置

	'APP_S_CACHE_TIME'  => 60,
	'DB_SQL_BUILD_CACHE' => true,
	'DB_SQL_BUILD_LENGTH' => 20,
    'DB_SQL_BUILD_QUEUE' => 'xcache',
	
	'HTML_CACHE_ON' => true,
	'HTML_CACHE_TIME' => 60,
	
	
	
    'UPLOAD_SAVE_PATH' => '../res/',
    
    'IP_LOCATION_URL' =>  'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=',
    'WEB_BASE_URL'    =>  'http://www.jh100.com/',
    'WEB_RES_URL'     =>  'http://res.jh100.com/',
    'WEB_IMG_URL'     =>  'http://image.jh100.com/',
	
	'WEB_MOBILE_URL' => 'http://m.jh100.com',
	'WEB_PC_URL' => 'http://www.jh100.com',
	
);