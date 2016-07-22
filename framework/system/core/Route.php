<?php
defined('BASEPATH') or exit('access not allowed !');

/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2016/7/21
 * Time: 18:16
 *
 * 处理请求参数，转发
 * 解析传递的url参数（按照本框架制定的规则）
 */
class Core_Route
{
    protected $url_type;
    protected $request_uri;
    protected $params_uri = array();
    protected $support_url_type = array(1,2);

    public function __construct($config_url_type)
    {
        $this->request_uri = $_SERVER['REQUEST_URI'];
        $this->set_uel_type($config_url_type);
    }

    /**
     * 设置url的解析方式
     * @param $config_url_type
     */
    public function set_uel_type($config_url_type)
    {
        if(!in_array($config_url_type, $this->support_url_type))
        {
            trigger_error('no support for the url type');
        }
        $this->url_type = $config_url_type;
    }

    /**
     * 解析路由
     */
    public function parse_route()
    {
        if($this->url_type == 1)
        {
            //标准模式normal
            $this->parse_route_normal();
            return $this->params_uri;
        }
        else
        {
            //slashes 模式
            //TODO
        }

    }

    public function parse_route_normal()
    {
        $request = $this->request_uri;

        $request_arr = explode('?', $request, 2);
        $request_str = isset($request_arr['1']) ? $request_arr['1'] : array();
        $request_param = explode('&', $request_str);

        $route_params = array();
        if(!empty($request_param))
        {
            foreach($request_param as $param)
            {
                @list($key, $value) = explode('=', $param);
                $route_params[$key] = $value;
            }
        }

        $this->params_uri = array(
            'application' =>isset($route_params['app']) ? $route_params['app'] : '',
            'controller' => isset($route_params['c']) ? $route_params['c'] : '',
            'action' =>isset($route_params['a']) ? $route_params['a'] : '',
            'params' => $route_params,
        );

    }

//end of the class
}
//end of the file