<?php
return array(
	//'配置项'=>'配置值'
    'APP_STATUS' => 'debug',
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'nb_system', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    "STEP_MAIL" => 7,
    "STEP_TAKE" => 6,
    "STEP_FINISH" => 5,
    "STEP_PASS" => 4,
    "STEP_CHECK" => 3,
    "STEP_TEST" => 2,
    "STEP_ASSIGN" => 1,
    "STEP_INIT" => 0,
    "TIME_INTERVAL"=>10 //新报告填充数据时间间隔
/*    'SITE_INFO' =>$site_config,
/*    'TIMER' =>$timer_config*/
/*    'DB_TYPE' => 'sqlsrv', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'dbNBTest', // 数据库名
    'DB_USER' => 'sa', // 用户名
    'DB_PWD' => '', // 密码
    'DB_PORT' => '1433', // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集*/
);