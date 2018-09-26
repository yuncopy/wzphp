<?php

/**
 *
 * 公共函数
*/

/**
 * 打印函数
 *
 * @author chenhuian
 * @param mixed $var 变量值
 * @return mixed
 */
if (!function_exists('p')) {

    function p($var)
    {
        if (is_string($var)) {
            var_dump($var);
        } elseif (is_array($var)) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        } elseif (is_object($var)) {
            print_r($var);
        }
    }
}



/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
if (!function_exists('redirect')) {
    function redirect($url, $time = 0, $msg = '')
    {
        //多行URL地址支持
        $url        = str_replace(array("\n", "\r"), '', $url);
        if (empty($msg)) {
            $msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
        }
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header('Location: ' . $url);
            } else {
                header("refresh:{$time};url={$url}");
                echo($msg);
            }
            exit();
        } else {
            $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0) {
                $str .= $msg;
            }
            exit($str);
        }
    }
}



/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
if (!function_exists('get_client_ip')) {
    function get_client_ip($type = 0, $adv = false)
    {
        $type       =  $type ? 1 : 0;
        static $ip  =   null;
        if ($ip !== null) {
            return $ip[$type];
        }
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }
                $ip     =   trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

}

/**
 *
 * 全局安全过滤
 *
 * @author chenhuian
 * @param
 *
*/
if (!function_exists('input_filter')) {
    function input_filter(&$value)
    {
        // TODO 其他安全过滤

        // 过滤查询特殊字符
        if (preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i', $value)) {
            $value .= ' ';
        }
    }
}



/**
 * 记录和统计时间（微秒）和内存使用情况
 * 使用方法:
 * <code>
 * G('begin'); // 记录开始标记位
 * // ... 区间运行代码
 * G('end'); // 记录结束标签位
 * echo G('begin','end',6); // 统计区间运行时间 精确到小数后6位
 * echo G('begin','end','m'); // 统计区间内存使用情况
 * 如果end标记位没有定义，则会自动以当前作为标记位
 * 其中统计内存使用需要 MEMORY_LIMIT_ON 常量为true才有效
 * </code>
 * @param string $start 开始标签
 * @param string $end 结束标签
 * @param integer|string $dec 小数位或者m
 * @return mixed
 */

if (!function_exists('G')) {
    function G($start, $end = '', $dec = 4)
    {
        static $_info       =   array();
        static $_mem        =   array();
        if (is_float($end)) { // 记录时间
            $_info[$start]  =   $end;
        } elseif (!empty($end)) { // 统计时间和内存使用
            if (!isset($_info[$end])) {
                $_info[$end]       =  microtime(true);
            }
            if ($dec=='m') {
                if (!isset($_mem[$end])) {
                    $_mem[$end]     =  memory_get_usage();
                }
                return number_format(($_mem[$end]-$_mem[$start])/1024);
            } else {
                return number_format(($_info[$end]-$_info[$start]), $dec);
            }
        } else { // 记录时间和内存使用
            $_info[$start]  =  microtime(true);
            $_mem[$start]   =  memory_get_usage();
        }
        return null;
    }
}



/**
 * 添加和获取页面Trace记录
 * @param string $value 变量
 * @param string $label 标签
 * @param string $level 日志级别
 * @param boolean $record 是否记录日志
 * @return void|array
 */
if (!function_exists('trace')) {
    function trace($value = '[wz]', $label = '', $level = 'DEBUG', $record = false)
    {
        return WzPHP\Core\Bootstrap::trace($value, $label, $level, $record);
    }

}
