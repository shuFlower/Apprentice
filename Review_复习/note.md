

TCP

ip可以写内网的IP吗

差点忘记关闭socket

直接只write，不read

在浏览器运行，命令行运行的差别

php换行符: "\n"

socket_close()不关闭会怎么样

因为浏览器运行的，所以才会出现端口一直占用么



UDP

创建以后怎么做？怎样对接端口再直接发送（是无连接的）


监听的意义在于，在于判断有没有连接，如果没有就【断开...或是准备什么资源】
udp无连接，所以不需要监听

socket_send(),socket_sendto()的区别

socket_recvfrom()方法有点忘记了

server端不用socket_close()???
udp不用socket_close()???




HTTP

为什么读取body剩余信息的时候，socket_read($socket, 512);  //变成了512？


$body[$contentLen]数组？？


php 解压的方法


