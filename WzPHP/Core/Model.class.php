<?php


namespace Core;

use Medoo\Medoo;

/**
 * WzPHP 模型核心类
 */
class Model extends Medoo
{


    /**
     * 实例化数据库
     *
     * 使用手册https://medoo.lvtao.net/doc.php
    */
    public function __construct()
    {
        // 初始化配置
        try {
            parent::__construct([
                'database_type' => DB_TYPE,
                'database_name' => DB_NAME,
                'server'        => DB_HOST,
                'username'      => DB_USER,
                'password'      => DB_PWD,
                'charset'       => DB_CHARSET
            ]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

    }


}
