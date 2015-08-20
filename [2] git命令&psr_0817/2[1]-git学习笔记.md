title: '[1-1] git学习笔记'
date: 2015-08-19 18:19:12
author: flower
category: 收徒
tags: [git]
---

##git-windows##

**1.工具**
下载msysgit<br/>
**2.个人账号配置**
git config --global  user.name shuFlower
git config --global  user.email "1510276107@qq.com"<br/>
个人觉得这里的设置就是为了代码提交记录，类似于svn<br/>
**3.ssh**
让自己的本地与git连接，代码就可以pull、push，通过配对的密匙连接，使用ssh连接方式。
生成密匙对：将本地的ssh public key 注册到我的git账户中，就完成了一个认证；这样每次本地push、pull的时候将ssh private key带过去，认证通过，pull、push成功

> 简而言之：
> git账户（锁）
> 本地电脑（钥匙）

 1.生成key
 

    ssh-keygen -t rsa -C  your_email@youremail.com 
    //-t rsa 加密
    

 2. 将key添加到git账户中
 

    (1)执行后，cd ~/.ssh，可以看到 id_rsa, id_rsa.pub
    (2)cat id_rsa.pub ，复制里面的key，然后登陆git账户：
    

> git->SSH Kyes ->Add SSH key

**4.正式开始**
(1)git、本地创建hello
(2)本地

    (1)git bash 
    (2)git init (此时git会在这个文件夹下创建一个隐藏目录，这个目录就是本地库了)
(3)绑定本地，git

    git remote add origin git@github.com:shuFlower/hello.git
(4)pull

    git pull origin master

(5)push

    git add client.php
    git commint -m "提交客户端代码client"
    git push origin master
    /*
    更新的情况
    git status
    git commit -a -m "message" //提交所有
    git push origin master
    */

(6)clone

    git clone https://github.com/xinghalo/WorldStar.git
    或
    git clone git@github.com:shuFlower/hello.git

    



