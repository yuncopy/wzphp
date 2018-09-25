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
