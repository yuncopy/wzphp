<?php
/**
 * Created by PhpStorm.
 * User: chenhuian
 * Date: 2018/9/25
 * Time: 14:41
 */


//定义数据库信息
define("DB_TYPE",'mysql');          //数据库类型
define("DB_HOST",'192.168.6.71');   //服务器地址
define("DB_PREFIX",'');             //数据库表前缀
define("DB_NAME",'yoshop_db');      //数据库名
define("DB_USER",'root');           //用户名
define("DB_PWD",'NvGHHsQvo3!90YS@');//密码
define("DB_PORT",3306);             //端口
define("DB_CHARSET",'utf8');        //数据库编码默认采用utf8


/* 错误设置 */
define('ERROR_MESSAGE','页面错误！请稍后再试～'); //错误显示信息,非调试模式有效
define('ERROR_PAGE',''); // 错误定向页面
define('ERROR_SHOW_MSG',false); // 显示错误信息
define('ERROR_MAX_RECORD',100); // 每个级别的错误信息 最大记录数
define('ERROR_EXCEPTION_FILE',PUBLIC_PATH.'exception.html'); // 异常页面路径


//日志设置
define('LOG_RECORD',false); // 默认不记录日志
define('LOG_TYPE','File'); // 日志记录类型 默认为文件方式
define('LOG_PATH',RUNTIME_PATH.'logs/'); // 日志记录路径
define('LOG_LEVEL','EMERG,ALERT,CRIT,ERR,NOTIC'); // 允许记录的日志级别
define('LOG_FILE_SIZE',2097152); // 日志文件大小限制
define('LOG_EXCEPTION_RECORD',false); // 是否记录异常信息日志

//全局功能配置
define('IS_VARS_FILTER',true); // 是否需求过滤 $_POST $_GET $_REQUEST
define('SHOW_PAGE_TRACE',false); // 显示页面Trace信息，调试页面



