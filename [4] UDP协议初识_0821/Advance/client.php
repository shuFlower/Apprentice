<?php
/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2015/8/21
 * Time: 16:43
 *
 * UDP连接-client
 */

try {
    //连接ip，端口
    $ip = "localhost";
    $port = "8080";

    //创建socket
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    if (!$socket) {
        throw new Exception(socket_last_error($socket));
    }
    //发送消息(DUP是无连接协议，不连接)
    $buf = 'hello';
    $len = strlen($buf);
    socket_sendto($socket, $buf, $len, 0, $ip, $port);

    usleep(5000);
    socket_recvfrom($socket, $receve, 1024, 0, $ip, $port);
    echo $receve;

} catch (Exception $exception) {
    echo $exception->getMessage();
}