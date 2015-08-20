<?php
/**
 * Created by PhpStorm.
 * User: flower
 * Date: 2015/8/18
 * Time: 21:58
 */

try
{
    //连接ip，端口
    $ip = "localhost";
    $port = "8080";

    //创建socket
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if(!$socket) {
        throw new Exception(socket_last_error($socket));
    }
    //连接
    $connect = socket_connect($socket, $ip, $port);
    if(!$connect){
        throw new Exception(socket_last_error($socket));
    }

    //连接成功，发送
    socket_write($socket, 'hello');
    //接送返回的消息
    $accpet_msg = socket_read($socket, 1024);
    echo $accpet_msg;

    //关闭连接
    socket_close($socket);

}catch (Exception $exception){
    echo $exception->getMessage();
}

//end of the file
