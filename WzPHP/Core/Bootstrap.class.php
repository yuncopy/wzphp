<?php


namespace Core;

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

        //异常处理

        // URL调度
        self::dispatch();

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
            ROOT_PATH,  // 根目录
            APP_PATH,  // 项目目录
            WZ_PATH,   // 类文件目录
            CORE_PATH, // 核心目录
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

        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : false;
        $path_data = ['', 'index', 'index'];
        if ($path_info) {
            $path_data = $p_data  =   explode('/', trim($path_info, '/'));
            $path_count = count($path_data);
            if($path_count <=2){
                array_unshift($path_data, '');
            }else{

                //路由
                $path_data = array_slice($path_data, 0, 3);
                $path_data[0] = strtolower($path_data[0]) == 'index' ? '' : $path_data[0];

                //参数
                $parameter = array_slice($p_data, 3);
                if($parameter){
                    $c = count($parameter);
                    for($i=0;$i<$c;$i+=2){
                        $_GET[$parameter[$i]] = isset($parameter[$i+1]) ? $parameter[$i+1] : '';
                    }
                }
            }
        }

        $path_data = array_map(function ($v){
            return ucfirst($v);
        },$path_data);

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
            $action = self::$_action . 'Action';
            if (method_exists($controller, $action)) {
                $controller->$action(); //使用类中方法
            } else {
            }
        }

        return new self();
    }
}
