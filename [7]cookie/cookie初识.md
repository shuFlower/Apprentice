

###**cookie**###

####**cookie的引出**####
http协议支持我们浏览网页，但是http协议是一个无状态的协议（没有记忆：同一个client第1s与第2s的请求会被视为2个完全没有关系的独立请求）.cookie就是一种记忆工具，跟踪一个会话。

------------------------------------------------------------------------------------------------------------------

####**cookie是什么**####
####1.实质
Cookie实际上是存储在客户端的一小段文本信息

**cookie的几大元素**
1. **key=>value** ： Cookie对象使用key-value属性对的形式保存用户状态

2.**max-age**:如果为正数，则该Cookie在maxAge秒之后失效。如果为负数，该Cookie为临时Cookie，关闭浏览器即失效，浏览器也不会以任何形式保存该Cookie。如果为0，表示删除该Cookie。默认为–1

> 		正数：在XXX秒后失效，会持久化，写在计算机本地的文件中，直到设置的时间才会失效；
> 		负数：是临时cookie，不写文件持久化，但会记录在浏览器的内存中，关闭浏览器即消失了； 	
> 	    0：删除cookie的一种方式


3.**secure** :  该Cookie是否仅被使用安全协议传输。安全协议。安全协议有HTTPS，SSL等，在网络上传输数据之前先将数据加密。默认为false

4.**path ** :  如果设置为“/”，则本域名下contextPath都可以访问该Cookie

5.**domain** :  如果设置为“.google.com”，则所有以“google.com”结尾的域名都可以访问该Cookie

 6.**comment** :  该Cookie的用处说明。浏览器显示Cookie信息的时候显示该说明

7.**version** :  版本号
 

------------------------------------------------------------------------------------------------------------------


####2.产生
客户端请求server以后，server需要记住client，就在response中携带cookie信息，浏览器会解析这些cookie信息，并将cookie存储起来

    server端的response返回的cookie信息
    Set-Cookie:BDSVRTM=8; path=/
    
    Set-Cookie:BD_HOME=0; path=/           SetCookie:H_PS_PSSID=14345_1462_12826_14429_12868_16937_17000_16934_17003_17072_15747_12430_13932_16969_16867_17051; path=/; domain=.baidu.com
    
    Set-Cookie:__bsi=17818907614508579870_00_30_R_N_11_0303_C02F_N_I_I_0; expires=Tue, 01-Sep-15 23:11:18 GMT; domain=www.baidu.com; path=/

####3.使用
当浏览器请求这个网站的时候，就会将对应的cookie信息，放在request的请求信息中一起提交给server，server端根据提交的cookie验证用户
####4.修改&&删除：

server端可以通过返回的response的参数来修改/删除client端的cookie；
client端也可以通过js或其他前端语言修改cookie


    修改：set-cookie
    删除：set-max-age = 0

TIPS：浏览器请求的时候，只提交key-value的值，其他 max-age,domian,secure 等参数是浏览器用于处理cookie的参数。

------------------------------------------------------------------------------------------------------------------

####**cookie的具体内容**####
浏览器中地址栏：javascript:alert(document.cookie); 可以查看到这个网站的cookie信息，
 以www.baidu.com 为例输出的信息：
 

     BAIDUID=D4526026197890A21D311A7B29527B7F:FG=1;    
     BIDUPSID=D4526026197890A21D311A7B29527B7F; 
     PSTM=1439639980; 
     H_PS_SKIN_GO=4; 
     MCITY=-340%3A;
     BD_CK_SAM=1; 
	 BD_HOME=0;          H_PS_PSSID=14345_1462_12826_14429_12868_16937_17000_16934_17003_17072_15747_12430_13932_16969_16867_17051;
     BD_UPN=12314353
对应7个cookie值

------------------------------------------------------------------------------------------------------------------

**与session的区别**
1.数据存放：

    cookie  存放在客户的浏览器上（client端）
    session 放在服务器上（server端）

2.安全：
cookie不是很安全，别人可以分析存放在本地的COOKIE并进行COOKIE欺骗
   考虑到安全应当使用session。

3.服务器性能影响：
session会在一定时间内保存在服务器上。当访问增多，会比较占用你服务器的性能
   考虑到减轻服务器性能方面，应当使用COOKIE。

4.大小：
单个cookie保存的数据不能超过4K，很多浏览器都限制一个站点最多保存20个cookie。

5.一般的做法：
   将登陆信息等重要信息存放为SESSION
   其他信息如果需要保留，可以放在COOKIE中

------------------------------------------------------------------------------------------------------------------
**Others**
1.编码
cookie中的中文字符会进行编码转码
cookie支持：unicode,ascii,二进制编码


2.浏览器：
提交cookie的时候，只提交key=>value，其他字段作为浏览器的管理cookie的依据

    RequesHeader:只有key=>value
     Cookie:BAIDUID=D4526026197890A21D311A7B29527B7F:FG=1;     BIDUPSID=D4526026197890A21D311A7B29527B7F;
       PSTM=1439639980;
        BD_HOME=0; BD_UPN=12314353; 
        H_PS_SKIN_GO=8; H_PS_SKIN_GI=1; H_PS_645EC=2ab6DRs4%2FqD9MraE3z31agEeKFnhnTjFw4fUwy6WZxjqcuDJJ2Eyb4aFh9I; 
        BD_CK_SAM=1; H_PS_PSSID=14345_1462_12826_14429_12868_16937_17000_16934_17003_17072_15747_12430_13932_16969_16867_17051;
         BDSVRTM=0

3.cookie跨域

image.baidu.com
file.baidu.com
domain:.baidu.com

4.secure
https,ssl协议中支持secure=true 类的cookie，可以进行传输

5.js操作cookie
Js可以操作cookie，但是A网站的js不能操作B网站的cookie（W3C标准）


------------------------------------------------------------------------------------------------------------------
**Question**
如果想要两个域名完全不同的网站共有Cookie，可以生成两个Cookie，domain属性分别为两个域名，输出到客户端?????	

cookie路径

secure属性并不能对Cookie内容加密，因而不能保证绝对的安全性。如果需要高安全性，需要在程序中对Cookie内容加密、解密，以防泄密





参考链接：http://blog.csdn.net/yangdelong/article/details/4792763

