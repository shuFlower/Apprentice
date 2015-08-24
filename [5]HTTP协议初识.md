###应用层协议HTTP###

http协议的总体地位：
5层网路协议的最高层：物理、数据链路、网络层、运输层，应用层
C/S：基于TCP协议
面向事务：1.建立tcp连接；2.请求文档；3.返回文档；4释放连接
无连接：server完成一次3次握手后，断开连接
无状态协议：没有记忆，不会记忆任何上次请求的信息
通信的内容是：各种资源文件

**（1）一次http请求的故事**
 1. 浏览器输入: www.baidu.com/a.php
 
aa.php

    <?php
    echo "hello world";

 2. 寻找对应的服务器ip地址
 > 1.首先读取本地的system32中的hosts文件，如果有对应的：ip - domain，请求对应的ip
> 2.如果步骤1中没有，那么去查询外网的DNS，获取ip地址

 3.建立TCP连接，去请求服务器（对php而言就是我们的apache服务器了）
     请求的时候会携带参数：
 

    IP Address:
    Request method:
    Accept:
    Accept-Encoding:
    Accept-Language:
    Connection:
    Host:
    ......
    
    
   4.解析
   

> 解析请求的是哪个主机
> 解析请求的是哪个站点
> 解析请求的的文件资源

5.请求资源文件

6.这段代码在服务端执行（如果有请求其他的资源文件就直接返回给浏览器处理）


    <?php
    echo "hello world";

7.释放连接
8.服务器返回请求结果到浏览器端显示



**（2）无连接**
http无连接的意义在于：client请求一次，server返回，然后sever收到来自client的ack以后就断开连接，简单、快捷

但是更多时候请求的内容比较复杂，一次请求不满足需求，多次重复建立-断开，建立-断开，反而更消耗资源、低效，所以现在是keep-alive的流式的请求模式（保持连接）

参考文献：http://network.chinabyte.com/240/13310240.shtml