<?php
defined('BASEPATH') or exit('access not allowed !');

/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2016/7/20
 * Time: 19:43
 */

//定义几个系统目录

//define the path of the system files
define('SYSPATH',dirname(__DIR__));
define('SYSCOREPATH',SYSPATH.'/core');

//define the path of the app files
define('APPPATH', dirname(dirname(__DIR__)).'/app');

define('LIBPATH', APPPATH.'/lib');

define('VIEWPATH', APPPATH.'/view');

define('CONTROLLER', APPPATH.'/controller');

define('MODEL', APPPATH.'/model');


class Appliaction
{
    static $config;

    public static function init()
    {
        //load the default files
        self::autoload();
    }

    public static function run($config)
    {
        // load the config
        self::$config = $config;

        //initialize the basic variables
        self::init();

        //parse route
        $router = new Core_Route($config['system']['route']['url_type']);
        $router_params = $router->parse_route();

        //diatribute the request
        self::route_distribute($router_params, $config);





    }

    /**
     * 自动加载
     */
    public static function autoload()
    {
        $auto_load_arr = array(
            SYSCOREPATH.'/Controller.php',
            SYSCOREPATH.'/Model.php',
            SYSCOREPATH.'/Common.php',
            SYSCOREPATH.'/Route.php',
        );

        if(empty($auto_load_arr))
        {
            return;
        }

        foreach($auto_load_arr as $auto_file)
        {
            if(file_exists($auto_file))
            {
                require_once $auto_file;
            }
            else
            {
                //throw one user-level error,we can do the self-defined things on these errors
                trigger_error('core files does not exist');
            }
        }
    }

    /**
     * 路由功能分发
     * @param $router_params
     * @param $config
     */
    public static function route_distribute($router_params, $config)
    {
        $application = isset($router_params['application']) ? $router_params['application'] : '';
        $controller = isset($router_params['controller']) ? $router_params['controller'] : $config['system']['route']['default_controller'];
        $action = isset($router_params['action']) ? $router_params['action'] : $config['system']['route']['default_action'];
        $params = isset($router_params['params']) ? $router_params['params'] : array();

        $application_file = $application ? CONTROLLER.'/'.$application.'/' : CONTROLLER.'/';
        $controller_file = $application_file.$controller.'.php';

        if(file_exists($controller_file))
        {
            require_once $controller_file;
        }
        else
        {
            trigger_error('the controller not found, bad request');
        }

        //成才controller 类对象
        $controller_obj = new $controller;

        if(!method_exists($controller_obj, $action))
        {
            trigger_error('request method '.$action.' is not defined');
        }

        //调用方法
        $controller_obj->$action();

        //TODO 参数的处理


    }


//end of the class
}
//end of the file
