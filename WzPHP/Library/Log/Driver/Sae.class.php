<?php

/**
 * 第三方系统
 *
*/
namespace WzPHP\Library\Log\Driver;

class Sentry {

    protected $config  =   array(
        'log_time_format'   =>  ' c ',
    );

    // 实例化并传入参数
    public function __construct($config=array()){
        $this->config   =   array_merge($this->config,$config);
    }

    /**
     * 日志写入接口
     * @access public
     * @param string $log 日志信息
     * @param string $destination  写入目标
     * @return void
     */
    public function write($log,$destination='') {


    }
}
