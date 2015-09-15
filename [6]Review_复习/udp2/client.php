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
 * client 端
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
    //发送
    $msg = 'hello';
    $len = strlen($msg);
    socket_sendto($socket, $msg, $len, 0, $ip, $port);
    echo 'client send : ' . $msg . "\n";
    //接收
    socket_recvfrom($socket, $accept_msg, 1024, 0, $ip, $port);
    echo 'client accept : ' . $accept_msg;

    //关闭连接
    socket_close($socket);

} catch (Exception $exception) {
    echo $exception->getMessage();
}