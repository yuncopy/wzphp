<?php



/**
 *
 * 公共入口文件，项目的启动文件
 *
 * 1、定义框架常量
 * 2、加载公共函数库
 * 3、启动项目
 *
**/



const THINK_VERSION = '1.0.1';  //版本信息
const EXT           = '.class.php'; // 类文件后缀

//1、定义常量
defined('APP_DEBUG') or define('APP_DEBUG', true); // 是否调试模式
defined('WZ_PATH') or define('WZ_PATH',ROOT_PATH.'/WzPHP/');   //WzPHP目录
defined('APP_PATH') or define('APP_PATH',ROOT_PATH.'/App/');   //APP目录
defined('CORE_PATH') or define('CORE_PATH', WZ_PATH.'Core/'); // Core类库目录
defined('CONF_PATH') or define('CONF_PATH', WZ_PATH.'Conf/'); // 配置库目录
defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH.'/Runtime/'); // 运行时目录
defined('PUBLIC_PATH') or define('PUBLIC_PATH', ROOT_PATH.'/Public/'); // 公共目录
defined('IS_WIN') or define('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
defined('IS_CLI') or define('IS_CLI',PHP_SAPI=='cli'? 1   :   0);
define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);


//2、加载函数库和引入composer自动加载类
include ROOT_PATH.'/vendor/autoload.php';

include WZ_PATH.'Function.php';

include CONF_PATH.'Common.php';

//3、加载核心引导类启动项目
require CORE_PATH.'Bootstrap'.EXT;


//4、应用初始化
\WzPHP\Core\Bootstrap::start();



