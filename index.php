<?php


/**
 *
 * 入口文件
 *
**/

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die('require PHP > 5.3.0 !');
}

defined('ROOT_PATH') or define('ROOT_PATH', str_replace("\\", "/", dirname(__FILE__))); //(兼容window和linux 系统 斜线路径)

//引入公共文件
require ROOT_PATH.'/WzPHP/WzPHP.php';
