<?php
defined('BASEPATH') or exit('access not allowed !');

/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2016/7/19
 * Time: 16:46
 *
 * 一些数据的：全局配置参数
 * 1. 数据库
 * 2. 系统配置
 * 3. 通用变量
 */

/**
 * 数据库配置
 */
$config['system']['db'] = array(
    'host'=>'127.0.0.1',
    'database_name'=>'app',
    'user_name'=>'root',
    'password'=>'',
);

/**
 * 系统配置
 */

/**
 * url解析方式
 */
$config['system']['route'] = array(
    'default_controller' => 'login',
    'default_action' => 'index',
    /*
     * ------------------------------------------------------
     * set the way to parse url,there are two ways(it defines the way how we get the variables):
     * normal : index.php?a=898&b=jkj
     * slashes: index.php/a/898/b/jkj
     * ------------------------------------------------------
     *
     */
    'url_type' => '1',  //1=normal  2=slashes
);

/**
 * 缓存
 */
$config['system']['cache'] = array();