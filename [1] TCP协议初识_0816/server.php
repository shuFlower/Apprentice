<?php
/**
 * Created by PhpStorm.
 * User: flower
 * Date: 2015/8/20
 * Time: 22:29
 *
 * tcp连接：服务端
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
    //接收消息
    $accpet = socket_accept($socket);
    if(!$accpet){
        throw new Exception(socket_last_error($socket));
    }

    //连接成功，接收
    $accpet_msg = socket_read($accpet, 1024);
    echo $accpet_msg;
    //接送返回的消息
    socket_write($accpet, 'world');

    //关闭连接
    socket_close($accpet);
    socket_close($socket);

}catch (Exception $exception){
    echo $exception->getMessage();
}

//end of the file