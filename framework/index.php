<?php
/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2016/7/19
 * Time: 16:46
 *
 * 单一入口： 入口文件
 * 入口文件：
 * 1、引入系统的驱动文件
 * 2、引入配置文件
 * 3、自动加载 autoload
 * 4、定义常量
 */

define('BASEPATH', realpath(__DIR__));

//引入系统的驱动文件
require_once(__DIR__ . '/system/core/App.php');

//引入配置文件
require_once(__DIR__.'/app/config/config.php');


//$query_str = ($_SERVER['REQUEST_URI']);
//$request_arr = explode('?', $query_str, 2);
//
//$request_str = isset($request_arr['1']) ? $request_arr['1'] : array();
//$request_param = explode('&', $request_str);
//
//$route_params = array();
//if(!empty($request_param))
//{
//    foreach($request_param as $param)
//    {
//        @list($key, $value) = explode('=', $param);
//        $route_params[$key] = $value;
//    }
//}




Appliaction::run($config);





