<?php
defined('BASEPATH') or exit('access not allowed !');

/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2016/7/20
 * Time: 20:36
 *
 * 核心controller
 * 1. 加载基础model
 * 2. 加载基础view
 *
 */
class Core_Controller
{
    public function __construct()
    {

    }

    public function load_model($model)
    {
        $model_file = MODEL.'/'.$model.'.php';
        if(file_exists($model_file))
        {
            require_once $model_file;

            $model_obj = new $model;
            return $model_obj;
        }
        else
        {
            trigger_error('the request model is not exist');
        }

        //TODO
        return 99;

    }

    public function load_view($view)
    {
        header("Content-Type: text/html;chaset=utf-8");
        if(file_exists(APPPATH.'/view/'.$view.'.html'))
        {

        }

    }




//end of the class
}
//end of the file