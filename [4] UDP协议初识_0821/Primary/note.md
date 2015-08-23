**UDP初识**

1.UDP，TCP差别

2.不同的函数

socket_write()    socket_sendto()  [send与sendto的差别]
socket_read()     socket_recvfrom()

3.这里的通信是怎么做的：
  server 创建socket以后，就一直监听那个接口
  client 创建socket以后，可以一直想那个socket发送消息
