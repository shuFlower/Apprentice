<?php
/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2015/8/27
 * Time: 9:48
 *
 * http-socket 方式抓取网页内容
 */


try {
    $ip = 'phpres.cn';
    $port = '80';

    //创建
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if(!$socket)
    {
        throw new Exception('socket create error :'.socket_last_error($socket));
    }

    //连接
    $connection = socket_connect($socket, $ip, $port);
    if(!$connection)
    {
        throw new Exception('socket create error :'.socket_last_error($socket));
    }


//    $header = <<<HEADER
//GET / HTTP/1.1
//Host: phpres.cn
//Connection: keep-alive
//Cache-Control: no-cache
//Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
//Upgrade-Insecure-Requests: 1
//User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36
//Accept-Language: zh-CN,zh;q=0.8
//HEADER;
    $header = <<<HEADER
GET / HTTP/1.1
Host: phpres.cn
Connection: keep-alive
Cache-Control: max-age=0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36
Accept-Language: zh-CN,zh;q=0.8,en;q=0.6
If-None-Match: W/"4b86-51e2ffdecbd00-gzip"
If-Modified-Since: Wed, 26 Aug 2015 05:19:16 GMT
HEADER;

    socket_write($socket, $header);
    $mesasge = socket_read($socket, 10240);
    print_r($mesasge);


}catch (Exception $exception) {
    echo $exception->getMessage();
}



