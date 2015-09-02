<?php

/**
 * 抓取网页
 *
 * @author Leo Yang <leo@phpres.cn>
 */

//连接ip，端口
$ip   = "phpres.cn";
$port = "80";
$response = <<<CONTEXT
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


CONTEXT;


try {
    //创建socket
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$socket) {
        throw new Exception(socket_last_error($socket));
    }
    //连接
    $connect = socket_connect($socket, $ip, $port);
    if (!$connect) {
        throw new Exception(socket_last_error($socket));
    }

    //连接成功，发送response
    socket_write($socket, $response);

    $header     = '';
    $contentLen = 0;
    $body       = '';
    // 解析request
    while ($buf = socket_read($socket, 1024)) {
        if (strpos($buf, "\r\n") === 0) {
            // 开头是换行, 头信息结束
            break;
        } elseif (strpos($buf, "\r\n\r\n") !== false) {
            //有两个换行, 头信息结束, 将多余的内容放到body
            list($t1, $t2) = explode("\r\n\r\n", $buf);
            $header .= $t1;
            $body = $t2;
            break;
        } else {
            // 继续读取
            $header .= $buf;
        }
    }

    // 如果body不包含换行, 说明第一行没读完, 继续读取
    while (strpos($body, "\r\n") === false) {
        $body .= socket_read($socket, 1024);
    }

    // 第一行代表内容大小, 剩余的为body内容
    list($contentLen, $body) = explode("\r\n", $body, 2);
    // 将16进制转换为10进制
    $contentLen = hexdec($contentLen);

    // 读取剩余内容
    while (!isset($body{$contentLen})) {
        $body .= socket_read($socket, 512);
    }

    // 去除多余的内容
    $body = substr($body, 0, $contentLen);

    echo $body;
    //关闭连接
    socket_close($socket);

} catch (Exception $exception) {
    echo $exception->getMessage();
}

//end of the file