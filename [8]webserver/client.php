<?php
/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2015/9/6
 * Time: 10:22
 *
 * 实现一个 web server：支持cookie，session
 */


try{
    /**
     * 编写一个tcp-socket，发送的消息为http报文，返回的为网页信息
     */

    //ip,port
    $ip = 'localhost';
    $port = '80';

    //创建socket
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$socket) {
        throw new Exception('socket create error : ' . socket_last_error($socket));
    }
    //连接
    $connection = socket_connect($socket, $ip, $port);
    if (!$socket) {
        throw new Exception('socket connect error : ' . socket_last_error($socket));
    }

    //HTTP报文
    $http_msg = <<<MESSAGE
GET / HTTP/1.1
Host:localhost
Cache-Control:max-age=0
Connection:keep-alive
Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Language:zh-CN,zh;q=0.8
If-Modified-Since:Wed, 26 Aug 2015 05:19:16 GMT
If-None-Match:W/"4b86-51e2ffdecbd00-gzip"
Upgrade-Insecure-Requests:1
User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.36


MESSAGE;

    //发送HTTP报文
    socket_write($socket, $http_msg);
    $get_msg = socket_read($socket, 10240000);
    print_r($get_msg);exit;


    //接收返回的网页信息
    $http_page =  httpPageDeal($socket);
    //返回网页信息
    echo $http_page;

    //关闭连接
    socket_close($socket);

}catch (Exception $exception){
    echo $exception->getMessage();
}

/**
 * 处理服务端返回的网页信息
 * @param resource $socket socket
 * @return string
 */
function httpPageDeal($socket){
    $header = '';
    $body = '';

    //分离header,body
    while ($msg = socket_read($socket, 1024)) {
        if (strpos($msg, "\r\n") === 0) {
            //报错信息
            break;
        } elseif (strpos($msg, "\r\n\r\n") !== FALSE ) {
            //分离 header,body;两个空行分离header,body
            list($massage1, $message2) = explode("\r\n\r\n", $msg);
            $header .= $massage1;
            $body = $message2;
            break;
        } else {
            //header没有读完
            $header .= $msg;
        }
    }

    //读取完整的body内容
    while (strpos($body, "\r\n") === FALSE){
        //没有读到换行，说明body第一行没有获取完整
        $body .= socket_read($socket, 1024);
    }

    //第一行：内容大小
    list($body_length, $body) = explode("\r\n", $body);
    //返回的大小16进制
    $body_length = hexdec($body_length);

    //读取body剩余的信息
    if($body_length == 0)
    {
        return $body = '';
    }
    while (!isset($body[$body_length-1])){
        $body .= socket_read($socket, 1024);
    }

    //返回网页信息
    return $body;
}