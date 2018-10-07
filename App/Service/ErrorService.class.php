<?php


/**
 * 错误码提示统一处理类
*/
namespace App\Service;

class ErrorService
{


    /**
     * 定义错误码返回信息
     *
     * @author chenhuian
    */
    static public $_map = [

        //公共错误码
        '200'=>'请求成功',
        '400'=>'请求失败',

        //用户相关30xx
        '3001'=>'用户登录成功',
        '3002'=>'用户登录失败',

        //鉴权相关40xx
        '4001'=>'鉴权成功',
        '4002'=>'鉴权失败',



    ];


    /**
     *
     * @author chennuian
     * @param mixed $code 错误码
     * @return string $message
     *
    */
    public static function getMsg($code = 0)
    {
        $code = (string)$code;
        $prefix = "[{$code}]";
        if (isset(self::$_map[$code])) {
            return $prefix.'-'.self::$_map[$code];
        }
        return $prefix;
    }


    /**
     * @author chenhuian
     * @param array $data 错误信息数组
     * @return boolean 设置是否成功
    */
    public static function setMsg($data=[])
    {
        if(is_array($data)){
            foreach ($data as $key => $message){
                self::$_map[$key] = $message;
            }
        }
        return false;
    }
}
