<?php
/**
 * Created by PhpStorm.
 * User: flower
 * Date: 2015/8/30
 * Time: 21:06
 *
 * Goal: 练习tcp , client 发送一个"hello"，server 返回一个"world"
 * PSR编码风格
 * try-catch 异常处理
 * server 端
 */

try {
    //ip,port
    $ip = '127.0.0.1';
    $port = '11198';

    //创建tcp
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$socket) {
        throw new Exception('socket create error : ' . socket_last_error($socket));
    }
    //绑定端口
    $bind = socket_bind($socket, $ip, $port);
    if (!$bind) {
        throw new Exception('socket bind error : ' . socket_last_error($socket));
    }
    //监听
    $listen = socket_listen($socket);
    if (!$listen) {
        throw new Exception('listen bind error : ' . socket_last_error($socket));
    }
    //接收
    $accept = socket_accept($socket);
    if (!$accept) {
        throw new Exception('accept bind error : ' . socket_last_error($socket));
    }
    socket_read($accept, 1024);
    socket_write($accept, 'world');

    //关闭连接
    socket_close($accept);
    socket_close($socket);

} catch (Exception $exception) {
    echo $exception->getMessage();
}