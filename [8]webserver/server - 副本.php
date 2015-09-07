<?php
/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2015/9/6
 * Time: 11:25
 *
 * server：
 * 1.socket连接
 * 2.解析http-request
 * 3.拼接http-response
 * 4.返回data给client：设置client的session
 * 5.client携带ssid，再次访问
 */

try
{
    //无时间限制
    set_time_limit(0);

    //连接ip，端口
    $ip = "localhost";
    $port = "80";

    //创建socket
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if(!$socket) {
        throw new Exception(socket_last_error($socket));
    }
    //绑定端口
    $handler = socket_bind($socket, $ip, $port);
    if(!$handler){
        throw new Exception(socket_last_error($socket));
    }
    //监听
    $listen = socket_listen($socket);
    if(!$listen){
        throw new Exception(socket_last_error($socket));
    }

    //连接，接收数据
    if ($accpet = socket_accept($socket)) {
        //连接成功，接收
        $accpet_msg = socket_read($accpet, 1024);
        //------------------------------------处理http协议-------------------------------------------------------
        //分离：请求行-请求体
        $msg_len = strlen($accpet_msg);
        if ($num = strpos($accpet_msg, "\r\n")) {
            $http_line     = substr($accpet_msg, 0, $num);
            $accpet_remain = substr($accpet_msg, $num+2, $msg_len);  //$num+2:回车、换行

            //分离：请求头-请求数据
            list($http_header, $http_data) = explode("\r\n\r\n", $accpet_remain);
        } else {
            $http_line = '';
            $http_header = '';
            $http_data = '';
            $response = returnError($code = 404);
            //返回error 404
            socket_write($accpet, $response);
            socket_close($accpet);
            exit;
        }

        //1.http报文请求行:http-line
        list($method, $url, $protocal) = explode(' ', $http_line);
        //2.报文请求:http-header
        $param_arr = explode("\r\n", $http_header);
        //3.报文请求数据:http-data

        //根据请求，返回数据：response
        $response = getResponse($http_line, $http_header, $http_data);
//        print_r($response);exit;




//        $session= '__ci_last_regenerate|i:1441529979;adminid|s:1:"1";';


        //------------------------------------处理http协议-------------------------------------------------------

        //接送返回的消息
        socket_write($accpet, $response);

        //关闭连接
        socket_close($accpet);
    }





//    while($accpet = socket_accept($socket)){
//        //连接成功，接收
//        $accpet_msg = socket_read($accpet, 1024);
//        echo $accpet_msg;
//        //接送返回的消息
//        socket_write($accpet, 'world');
//
//        //关闭连接
//        socket_close($accpet);
//    }

    //关闭连接
    socket_close($socket);

}catch (Exception $exception){
    echo $exception->getMessage();
}


/**
 * server-response 拼接响应报文，返回对应的url资源
 * @param string $http_line
 * @param string $http_header
 * @param string $http_data
 */
function getResponse($http_line, $http_header, $http_data){

    //4个部分：状态行、响应头、空行、响应数据
    $response_line = '';
    $response_header = '';
    $response_space = "\r\n\r\n";
    $response_data = '';  //data = len + resource
    $response_len = 0;
    $response_resource = 0;

    //1.http报文请求行:http-line
    list($method, $url, $protocal) = explode(' ', $http_line);

    //2.报文请求:http-header
    $param_arr = explode("\r\n", $http_header);


    if($method == 'GET')
    {
        //get
        if(empty($url))
        {
            //返回默认的index的信息
            $response_data = returnIndex();
        }
        else
        {
            //根据url获取资源文件
            $response_data = returnResource();
        }
    }else{
        //post
        $response_data = 'post method';
    }


    //1. line
    $return_code = '200';
    $return_code_comment = 'OK';
    $response_line = $protocal.' '.$return_code.' '.$return_code_comment."\r\n";


    //2. header
    $response_header_arr = array(
        'Date'=>gmdate('l, d F Y H:i:s ').'GMT',
        'Content-Type'=>'text/html',
        'Content-Length'=> mb_strlen($response_data, 'utf-8'),
//        'Content-Charset'=>'text/html',
//        'Content-Encoding'=>'',
//        'Content-Language'=>'',
        'Server'=>'myserver',
        'Connection'=>'Keep-Alive',
        'Keep-Alive'=>'timeout=5, max=100',
    );
    foreach($response_header_arr as $key=>$value)
    {
        $response_header .= "{$key}:{$value}\r\n";
    }

    $response = $response_line.$response_header."\r\n\r\n".$response_data;
    return $response;
}

/*
 * 资源信息
 */

function returnError($code){
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>comment</title>
</head>
<body>
 {$code}
</body>
</html>
HTML;

    return $html;
}

function returnIndex(){
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>comment</title>
</head>
<body>
hello world!
</body>
</html>
HTML;

    return $html;
}

function returnResource(){
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>comment</title>
</head>
<body>
<form action="com.php" method="post">
    输入口令获取密码：<input name="Comment">
    <input type="submit" value="提交">
</form>
</body>
</html>
HTML;

    return $html;
}


function dealPhp($command){
    $ssid = '';
    if($command == 'flower')
    {
        $password = '123456';
        $ssid = 7777777;  //有效期，设置，超时清空
    }
    else
    {
        $password = '盗窃者，已报警';
    }
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>comment</title>
</head>
<body>
    {$password}
</form>
</body>
</html>
HTML;

    return array(
        'Html' => $html,
        'SSID' => $ssid
    );
}

//--------------------annotation---------------------
///*
// * 解析 http 报文
// */
//function parseHttpRequest($accpet){
//    $accpet_msg = socket_read($accpet, 1024);
//    list($http_line, $http_remian) = explode("\r\n", $accpet_msg);
//
//
//
//}
//
///*
// * 解析http报文请求行
// * string $http_line
// */
//function parseHttpLine($http_line)
//{
//    list($method, $url, $protocal) = explode(' ', $http_line);
//
//}



//end of the file
