<?php

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
        if (isset(self::$_map[$code])) {
            return self::$_map[$code];
        }
        return null;
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
