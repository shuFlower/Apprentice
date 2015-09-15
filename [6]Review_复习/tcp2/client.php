<?php
/**
 * Created by PhpStorm.
 * User: flower
 * Date: 2015/8/30
 * Time: 21:25
 *
 * Goal: 练习tcp , 1.client 发送一个"hello"，2.server 返回一个"world"，3.重复1,2过程
 * PSR编码风格
 * try-catch 异常处理
 * client 端
 */

try {
    //ip,port
    $ip = '127.0.0.1';
    $port = '11198';

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
    //发送
    $msg = 'hello';
    socket_write($socket, $msg);
    echo 'client send : ' . $msg."\n";

    //接收
    $receive_msg = socket_read($socket, 1024);
    echo 'client receive : ' . $receive_msg;

    //关闭连接
    socket_close($socket);

} catch (Exception $exception) {
    echo $exception->getMessage();
}
