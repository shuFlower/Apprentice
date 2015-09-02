**HTTP网页抓取_note**

>     加这句会返回乱码？？？而浏览器里面有这一句就可以正常返回，程序模拟就会乱码
>     Accept-Encoding:gzip, deflate, sdch
>     因为：浏览器已经自动解压了，而我的模拟程序没有做这一步。所以就显示乱码了


    报文的请求头里面的字段顺序是无关的，可以随意的


    因为一个http请求报文是4部分:
    请求行+请求头+回车换行+请求数据
    没有：2个换行（==报文的：回车换行+请求数据），
    sever端解析的报文是错误的，所以没有返回正确的数据，所以client端报错warning:socket_read()
    TIPS：报文中的重要元素：回车符，换行符


    每段报文的大小限制bytes



    请求是url对应的资源文件


    为什么要每个1024个去读socket($sock, 1024)，也可以用2048，为什么不用，是出于什么考虑的么？
    socket_read($socket, $len);  //这里的$len长度1024


    GET、POST区别 GET一般用于获取/查询资源信息，而POST一般用于更新资源信息 GET方式提交的数据最多只能是1024字节
    GET的请求参数在：请求行的url里;POST的请求参数在：请求数据里(key-value)
    GET安全性比POST低，因为参数会暴露在url里面


> 尝试了HEAD请求方式:没有成功？？ 结果是超时：
>  `Fatal error: Maximum execution time of 30
> seconds exceeded in C:\xampp\htdocs\test\brower.php on line 78`
> 
> 
> 
> 响应的demo （返回的信息包含2部分：header,body header 2个换行以后就是body的内容）：
> 
>     HTTP/1.1 200 OK Date: Sat, 29 Aug 2015 10:23:08 GMT Content-Type: text/html Transfer-Encoding: chunked Connection: keep-alive
> Set-Cookie: __cfduid=dffa6baac53fd9c0a0c8699f817bdbf211440843788;
> expires=Sun, 28-Aug-16 10:23:08 GMT; path=/; domain=.phpres.cn;
> HttpOnly Last-Modified: Wed, 26 Aug 2015 05:19:16 GMT ETag:
> W/"4b86-51e2ffdecbd00-gzip" Vary: Accept-Encoding Server:
> yunjiasu-nginx CF-RAY: 21d789f0cd78171c-SZX 3f64
>     /*此处有一个换行*/ 
>     Leo Yang
>     
>     羡慕有故事的人，不像我，活了这么久，一个帅字竟贯穿一生






    返回的$contentLen是变化的？为啥？


    socket_read()多次，这里是怎么进行的：每次read去server端取一次(keep-alive的保持在一个连接中进行的)


    【connection 模式为keepalive，则该连接会保持一段时间，在该时间内可以继续接收请求】这段时间是多长，在哪里设置？？？


> HTTP/1.1 协议的持久连接有两种方式：
> 1.非流水线方式：客户在收到前一个响应后才能发出下一个请求;
> 2.流水线方式：客户在收到 HTTP 的响应报文之前就能接着发送新的请求报文;


    用8080端口访问：返回：源站宕机？？？


参考文章：

【http报文】 http://blog.csdn.net/zhangliang_571/article/details/23508953

【GET/POST方法的区别】 http://www.cnblogs.com/hyddd/archive/2009/03/31/1426026.html

【Accept-Encoding乱码】http://blog.csdn.net/cctv_hu/article/details/6018942