##note##


socket_create()  3个参数
socket_last_error()

ipv4地址
ipv6地址

除了socket_read(),socket_write()组合，应该还有其他的发、收的方法

**差别**
$socket_0 = socket_accept()
$socket_1 = socket_create()
！！！！！！
$accept == socket_accept($socket);accept是什么含义，收到的是什么信息，出错会怎么样，
这里返回的里层套接字和外层的差别是什么
socket_create() = $socket : 这是server段的套接字，监听 11197 端口的消息
socket_accept() = $accept : 这是server端与某个client端的一次连接




为什么用浏览器访问和命名窗口看不一样
浏览器访问会报错，而且会结束掉
是因为协议的原因？因为超时？