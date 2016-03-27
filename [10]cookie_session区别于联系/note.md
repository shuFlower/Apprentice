


总结陈词：

cookie、session的联系：
1. 本质上来说：session就是一种叫特殊名字的cookie（例如：SESSIONID、ci_seeisonid等，由服务端自行商议对齐读取的是对应叫什么的cookie）
    以叫“SESSIONID”为例：
   （1）初次访问时，浏览器没有对应的cookie，服务端自动生成一个cookie值进行URL地址重写，并将值Set_Cookie返回给浏览器
   （2）再次访问的时候，浏览器就会携带叫“SESSIONID”的cookie值到服务端
   （3）服务端读取这个SESSIONID对应的信息，识别出用户

2. 为了弥补HTTP协议无状态、不能跟踪会话识别用户的特点





区别：
1. 保存位置：cookie在客户端，session在服务端（一般放在服务器的内存中：较高的读取速度，但是同时要求session数据要精简）
2. cookie在客户端由浏览器来管理其存放位置，用户可以自行删除、管理；session在服务端，生命周期、文件位置、如何定义均在服务端




笔记：
1.一个cookie的例子
  //code
  setcookie('flower','beauty',time()+60,'/','91act.com',false,false);

  //参数
  setcookie(name,value ,xpire_time,path,domain ,security,httponly);
  已测试的几种情况：
  （1）当name，domain,path相同时就为同一个cookie,改变security，httponly参数是不会新创建一个cookie的
  （2）security=true：请求非安全协议的网站时，不会携带这个cookie
  （3）httponly=false: javascrip:alert(document.cookie);才可生效获取网站的cookie内容；当=true的时候，是禁止脚本读取的
  （4）服务端已经获取了需要的cookie值以后，并且没有修改/删除cookie的操作，那么http的response-headers就不会有：Set_Cookie的信息



2. 读取cookie、session、
   （1）读取cookie：$_COOKIE['flower']  //输出值：beauty
   （2）读取session：$_SESSION  //对应的session内容


3.验证cookie信息：
  （1）cookie中直接存用户名密码，加密以后传到服务端解密、查询数据库识别
  （2）cookie中保存用户名与时间戳，服务端查询数据库比对
  （3）类似于（2），但是服务端校验的是加密规则，不用查询数据库，cookie中保存完整的用户信息


4. 超时计算：
   当前时间 - 最后一次活跃时间 ？ maxAge: 大于则超时

5.浏览窗口内打开的新窗口会共享父窗口的cookie值（因为传递的SESSIONID-cookie是一样的，若是打开的是一个新域名下的页面，当然就不是同一个session了）

6.当浏览器端禁止掉cookie，服务端可以通过http-response:encode_URL重写地址将sessionid传递到服务端，实现身份识别（类似于：“aaa.php;sessionid=XXX”,既不会影响请求的文件名，也不会影响提交的地址栏参数）

7.服务器删除session文件的机制