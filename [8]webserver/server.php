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
    while ($accpet = socket_accept($socket)) {
        //连接成功，接收
        $accpet_msg = socket_read($accpet, 1024);

        //-------------------------处理http协议------------------
        //分离：请求行-请求体
        $msg_len = strlen($accpet_msg);
        if ($num = strpos($accpet_msg, "\r\n")) {
            $http_line     = substr($accpet_msg, 0, $num);
            $accpet_remain = substr($accpet_msg, $num+2, $msg_len);  //$num+2:回车、换行

            //分离：请求头-请求数据
            list($http_header, $http_data) = explode("\r\n\r\n", $accpet_remain);
        } else {
            //错误请求，返回error 400
            $http_line = '';
            $http_header = '';
            $http_data = '';
            $response = returnError($code = 400, 'Bad Request : server cannot resolute the request correctly.');
            socket_write($accpet, $response);
            socket_close($accpet);
        }

        //处理请求，返回数据：response
        $response = getResponse($http_line, $http_header, $http_data);

//        $session= '__ci_last_regenerate|i:1441529979;adminid|s:1:"1";';
        //-----------处理http协议--------------


        //接送返回的消息
        socket_write($accpet, $response);
        //关闭连接
        socket_close($accpet);
    }

    //关闭连接
    socket_close($socket);

}catch (Exception $exception){
    echo $exception->getMessage();
    //反应在client端，应该就是一个 5xx错误
}



/**
 * server-response 拼接响应报文，返回对应的url资源
 * @param $http_line
 * @param $http_header
 * @param $http_data
 * @return string
 */
function getResponse($http_line, $http_header, $http_data){

    //4个部分：状态行、响应头、空行、响应数据
    $response_header = '';

    //1.http报文请求行:http-line
    list($method, $url, $protocal) = explode(' ', $http_line);

    //2.报文请求:http-header
    $param_arr = array();
    $param_line_arr = explode("\r\n", $http_header);
    foreach ($param_line_arr as $k=>$v) {
        //key=>value
        list($type, $value) = explode(':', $v);
        $param_arr[$type] = $value;
    }

    //http-line处理：这里只做支持：get，post
    $response_cookie = '';
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
    }elseif($method == 'POST'){
        //post
        //$url默认就是com.php的处理了(实际的应该根据url去定位相应的文件，并返回运行结果)


        $client_ssid = isset($param_arr['Cookie']) ? $param_arr['Cookie'] : '';

        $php_deal_data = dealPhp($http_data, $client_ssid);
        $response_data = $php_deal_data['Html'];
        $response_cookie = $php_deal_data['SSID'];
    }
    else{
        //请求数据错误
        $response_data = returnError(405,'Method Not Allowed ');
    }

    //1. response-line
    $return_code = '200';
    $return_code_comment = 'OK';
    $response_line = $protocal.' '.$return_code.' '.$return_code_comment."\r\n";

    //2.response-header
    $response_len = mb_strlen($response_data, 'utf-8');
    $response_header_arr = array(
        'Date' => gmdate('l, d F Y H:i:s ') . 'GMT',
        'Content-Type' => 'text/html',
        'Content-Length' => $response_len,
//        'Content-Charset'=>'text/html',
//        'Content-Encoding'=>'',
//        'Content-Language'=>'',
        'set-cookie' => $response_cookie,
        'Server' => 'myserver',
        'Connection' => 'Keep-Alive',
        'Keep-Alive' => 'timeout=5, max=100',
    );
    if(empty($response_cookie))
    {
        unset($response_header_arr['set-cookie']);
    }

    foreach($response_header_arr as $key=>$value)
    {
        $response_header .= "{$key}:{$value}\r\n";
    }

    $response = $response_line.$response_header."\r\n".dechex($response_len)."\r\n".$response_data;
    return $response;
}

/*
 * 资源信息
 */

function returnError($code, $illustration){
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>comment</title>
</head>
<body>
 {$code},{$illustration}
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
    Please input the key to get password：<input name="Comment">
    <input type="submit" value="submit">
</form>
</body>
</html>
HTML;

    return $html;
}


function dealPhp($http_data, $client_ssid){

    $param = getPostParams($http_data);
    if(!isset($param['Comment']))
    {
        $password = 'param error';
        $ssid = '';
    }
    else
    {
        //读取的server上的ssid文件
        $ssid_id_arr = getSsidArr();
        if(!empty($client_ssid) && in_array($client_ssid,$ssid_id_arr))
        {
            //ssid用户，直接返回数据即可
            $password = '123456';  //这里假设密码是ssid对应可获取的数据
            $ssid = $client_ssid;

        }elseif (empty($client_ssid) && $param['Comment'] == 'flower') {
            //无ssid，但是验证通过，返回数据，生成ssid
            $password = '123456';
            $ssid = generateSsid();
        }elseif (empty($client_ssid) && $param['Comment'] != 'flower') {
            //无ssid，验证失败，返回空
            $password = '';
            $ssid = '';
        } else
        {
            $password = 'You are a thief,we have called 911';
            $ssid = '';
        }
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

/**
 * TODO 未设置超时的限制
 * 生成ssid
 * @return int
 */
function generateSsid(){
    //一个简单的生成规则
    $ssid_arr = array();
    for($i=0;$i<5;$i++)
    {
        $ssid_arr[] = rand(10000,99999);
    }
    $ssid = implode('_', $ssid_arr);
    //写入文件
    $ssid_file = __DIR__.'/ssid';
    if(!file_exists($ssid_file))
    {
        file_put_contents($ssid_file,'');
    }

    $handler = fopen($ssid_file, 'a+');
    //锁定，取得独占锁定（写入的程序）
    flock($handler, LOCK_EX);
    $content = fgets($handler);
    if(empty($content))
    {
        fputs($handler, $ssid);
    }
    else
    {
        fputs($handler,','.$ssid);
    }

    //要释放锁定（无论共享或独占）
    flock($handler, LOCK_UN);
    fclose($handler);

    return $ssid;
}

/**
 * 读取文件中的ssid数组
 * @return array
 */
function getSsidArr()
{
    //写入文件
    $ssid_file = __DIR__.'/ssid';
    $ssid_arr = array();
    if(file_exists($ssid_file))
    {
        $handler = fopen($ssid_file, 'r');
        $ssid_str = fgets($handler);
        if(!empty($ssid_str))
        {
            $ssid_arr = explode(',', $ssid_str);
        }
    }

    return $ssid_arr;
}

/**
 * 获取post参数
 * @param $post_params
 * @return array
 */
function getPostParams($post_params)
{
    $param = array();

    if(empty($post_params))
    {
        return $param;
    }
    //分离各个参数
    $param1 = explode('&', $post_params);
    if(!empty($param1))
    {
        foreach($param1 as $key=>$value)
        {
            list($k,$v) = explode('=', $value);
            $param[$k] = $v;
        }
    }
    return $param;
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
