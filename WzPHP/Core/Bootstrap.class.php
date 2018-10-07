<?php


namespace WzPHP\Core;

/**
 * WzPHP 引导核心类
 */
class Bootstrap
{


    // 类映射
    private static $_map      = array();
    private static $_module;
    private static $_controller;
    private static $_action;

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    public static function start()
    {
        // 注册AUTOLOAD方法
        spl_autoload_register('self::autoload');

        // 设定错误和异常处理
        register_shutdown_function('\WzPHP\Core\Bootstrap::fatalError'); //注册一个会在php中止时执行的函数
        set_error_handler('\WzPHP\Core\Bootstrap::appError');  //设置用户自定义的错误处理函数
        set_exception_handler('\WzPHP\Core\Bootstrap::appException');
        error_reporting( E_ALL );

        // URL调度
        self::dispatch();

        //安全过滤
        self::safeFilter();

        //运行应用
        self::run();
    }

    /**
     * 类库自动加载
     *
     *
     * 作用：解决在类相互调用实例化(new class)类时自动加载类文件
     *
     * 实例化类时我们要把类文件包含进来才能进行实例化对象
     * 实例化对象方式      new \core\route();
     * 包含类文件路径      $class = ROOT_PATH./core/route.php
     * 导入文件           include ($class);
     *
     * @param string $class 对象类名
     * @return void
     */
    public static function autoload($class)
    {

        $class_file_name       =   str_replace('\\', '/', $class).EXT;
        //定义自动加载类库目录
        $dirs = array(
            ROOT_PATH,   // 根目录
            APP_PATH,    // 项目目录
            WZ_PATH     // 核心类目录
        );

        if (isset(self::$_map[$class])) {
            return true;
        } else {
            foreach ($dirs as $dir) {
                $class_path = rtrim($dir, '/') . '/' . $class_file_name;
                if (file_exists($class_path) && is_file($class_path)) {
                    include_once($class_path);
                    self::$_map[$class] = $class_path; //性能考虑
                }
            }
        }
    }

    /**
     * URL映射到控制器
     * @access public
     * @return void
     */
    public static function dispatch()
    {

        //获取URL
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : false;
        $path_data = ['', 'index', 'index'];
        if ($path_info) {
            $path_data = $p_data  =   explode('/', trim($path_info, '/'));
            $path_count = count($path_data);
            if ($path_count <=2) {
                array_unshift($path_data, '');
            } else {
                //路由
                $path_data = array_slice($path_data, 0, 3);
                $path_data[0] = strtolower($path_data[0]) == 'index' ? '' : $path_data[0];

                //参数
                $parameter = array_slice($p_data, 3);
                if ($parameter) {
                    $c = count($parameter);
                    for ($i=0; $i<$c; $i+=2) {
                        $_GET[$parameter[$i]] = isset($parameter[$i+1]) ? $parameter[$i+1] : '';
                    }
                }
            }
        }

        $path_data = array_map(function ($v) {
            return ucfirst($v);
        }, $path_data);

        //解析数据
        list(self::$_module,self::$_controller,self::$_action) = $path_data;
    }


    /**
     *
     * 启动框架的方法
     * @author chenhuian
     * @date 2018年9月25日14:48:00
     *
     * 解析路由执行加载对应控制器-->执行控制器中对应的方法
     *
    */
    public static function run()
    {
        $controller_path = APP_PATH.'Controller/'.self::$_controller.'Controller'.EXT;
        if (is_file($controller_path) && file_exists($controller_path)) {
            include_once $controller_path;

            $_module = self::$_module ? "\\".self::$_module : '';
            $_controller = self::$_controller;
            $class_name = "\\App{$_module}\\Controller\\{$_controller}Controller";
            $controller = new $class_name(); //实例化通过URL解析的类
            $action = strtolower(self::$_action) . 'Action';
            if (method_exists($controller, $action)) {
                $controller->$action(); //使用类中方法
            } else {
            }
        }

        return new self();
    }


    /**
     * 致命错误捕获
     *
     * @author chenhuian
    */
    public static function fatalError()
    {
        //保存日志
        Log::save();
        if ($e = error_get_last()) {
            switch ($e['type']) {
                case E_ERROR:  //致命错误
                case E_PARSE:   //语法解析错误
                case E_CORE_ERROR: //PHP初始化启动过程中发生的致命错误,由PHP引擎核心产生的
                case E_COMPILE_ERROR: //致命编译时错误,由Zend脚本引擎产生的。
                case E_USER_ERROR: //用户产生的错误信息,由用户自己在代码中使用PHP函数 trigger_error()来产生的。
                    ob_end_clean(); //清空（擦除）缓冲区并关闭输出缓冲
                    self::halt($e);
                    break;
            }
        }
    }

    /**
     * 错误输出
     * @author chenhuian
     *
     * @param mixed $error 错误
     * @return void
     */
    public static function halt($error)
    {
        $e = array();
        if (APP_DEBUG || IS_CLI) {
            //调试模式下输出错误信息
            if (!is_array($error)) {
                $trace          = debug_backtrace();
                $e['message']   = $error;
                $e['file']      = $trace[0]['file'];
                $e['line']      = $trace[0]['line'];
                ob_start();
                debug_print_backtrace();
                $e['trace']     = ob_get_clean();
            } else {
                $e              = $error;
            }
            if (IS_CLI) {
                exit(
                    iconv(
                        'UTF-8',
                        'gbk',
                        $e['message']
                    ).PHP_EOL.'FILE: '.$e['file'].'('.$e['line'].')'.PHP_EOL.$e['trace']
                );
            }
        } else {
            //否则定向到错误页面
            $error_page         = ERROR_PAGE;
            if (!empty($error_page)) {
                redirect($error_page);
            } else {
                $message        = is_array($error) ? $error['message'] : $error;
                $e['message']   = ERROR_SHOW_MSG ? $message : ERROR_MESSAGE;
            }
        }

        // 包含异常页面模板
        include_once ERROR_EXCEPTION_FILE;
        exit;
    }


    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    public static function appError($errno, $errstr, $errfile, $errline)
    {


        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                ob_end_clean();
                $errorStr = "$errstr ".$errfile." 第 $errline 行.";
                if (LOG_RECORD) {
                    Log::write("[$errno] ".$errorStr, Log::ERR);
                }
                self::halt($errorStr);
                break;
            default:
                $errorStr = "[$errno] $errstr ".$errfile." 第 $errline 行.";
                self::trace($errorStr, '', 'NOTIC');
                break;
        }
    }

    /**
     * 添加和获取页面Trace记录
     * @param string $value 变量
     * @param string $label 标签
     * @param string $level 日志级别(或者页面Trace的选项卡)
     * @param boolean $record 是否记录日志
     * @return void|array
     */
    public static function trace($value = '[wz]', $label = '', $level = 'DEBUG', $record = false)
    {
        static $_trace =  array();
        if ('[wz]' === $value) { // 获取trace信息
            return $_trace;
        } else {
            $info   =   ($label?$label.':':'').print_r($value, true);
            $level  =   strtoupper($level);

            if ( IS_AJAX || !SHOW_PAGE_TRACE || $record) {
                Log::record($info, $level, $record);
            } else {
                if (!isset($_trace[$level]) || count($_trace[$level]) > ERROR_MAX_RECORD) {
                    $_trace[$level] =   array();
                }
                $_trace[$level][]   =   $info;
            }
        }
    }

    /**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
    public static function appException($e)
    {
        $error = array();
        $error['message']   =   $e->getMessage();
        $trace              =   $e->getTrace();
        if ('E'==$trace[0]['function']) {
            $error['file']  =   $trace[0]['file'];
            $error['line']  =   $trace[0]['line'];
        } else {
            $error['file']  =   $e->getFile();
            $error['line']  =   $e->getLine();
        }
        $error['trace']     =   $e->getTraceAsString();
        Log::record($error['message'], Log::ERR);
        // 发送404信息
        header('HTTP/1.1 404 Not Found');
        header('Status:404 Not Found');
        self::halt($error);
    }

    /**
     * 过滤关键字
     *
    */
    public static function safeFilter()
    {

        if (IS_VARS_FILTER) {
            // 全局安全过滤
            array_walk_recursive($_GET, 'input_filter');
            array_walk_recursive($_POST, 'input_filter');
            array_walk_recursive($_REQUEST, 'input_filter');
        }
        return true;
    }
}
