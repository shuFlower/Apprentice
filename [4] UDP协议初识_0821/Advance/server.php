<?php
/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2015/8/21
 * Time: 16:44
 *
 * UDP连接-server
 */

try
{
    //连接ip，端口
    $ip = "localhost";
    $port = "8080";

    //创建socket
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    if(!$socket) {
        throw new Exception(socket_last_error($socket));
    }
    //绑定端口
    $handler = socket_bind($socket, $ip, $port);
    if(!$handler){
        throw new Exception(socket_last_error($socket));
    }

    //接收消息
    socket_recvfrom($socket, $buf, 1024, 0, $ip, $port);
    echo $buf."<br/>";

    //发送消息
    $reply = 'world';
    $len = strlen($reply);
    socket_sendto($socket, $reply, $len, 0, $ip, $port);

}catch (Exception $exception){
    echo $exception->getMessage();
}