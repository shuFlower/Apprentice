<?php
/**
 * Created by PhpStorm.
 * User: flower
 * Date: 2015/8/30
 * Time: 22:02
 *
 * Goal: 练习udp , 1.client 发送一个"hello"，2.server 返回一个"world"，3.重复1,2
 * PSR编码风格
 * try-catch 异常处理
 * server 端
 */

try {
    //ip,port
    $ip = 'localhost';
    $port = '11197';

    //创建socket
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    if (!$socket) {
        throw new Exception('socket create error : ' . socket_last_error($socket));
    }
    //绑定
    $bind = socket_bind($socket, $ip, $port);
    if (!$bind) {
        throw new Exception('socket bind error : ' . socket_last_error($socket));
    }

    while (true) {
        //读取
        socket_recvfrom($socket, $accept_msg, 1024, 0, $ip, $port);
        //发送
        $send_msg = 'world';
        $len = strlen($send_msg);
        socket_sendto($socket, $send_msg, $len, 0, $ip, $port);
    }

    //关闭socket
    socket_close($socket);

} catch (Exception $exception) {
    echo $exception->getMessage();
}