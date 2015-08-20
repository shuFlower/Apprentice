title: '[1-2] psr学习笔记'
date: 2015-08-19 18:19:12
author: flower
category: 收徒
tags: [psr]
---
##psr-note##

**psr-1**

 1. 命名
 2. 从属规范：
    

> 声明和业务逻辑不能在同一个文件中

**psr-2**

 

>    1.代码必须使用4个空格符而不是 tab键 进行缩进  ??
>     2.每行字符个数80~120
>     3.{}规则

    class AdminDo
    {
	    function adminDo()
	    {
	        if ($varible1, $varible2) {
				//TODO
		    } elseif ($param1 > $param2) {
			    //TODO
		    }
	    }
    }
    



4.结尾：
（1）纯PHP代码文件必须省略最后的 ?> 结束标签
（2）空白行结尾
5.关键字必须全部小写
6.参数列表可以分列成多行，这样，包括第一个参数在内的每个参数都必须单独成行

    public function aVeryLongMethodName(
            ClassTypeHint $arg1,
            &$arg2,
            array $arg3 = []
        ) {
            // method body
        }
 7.$i++，无空格      

**psr-3** 
八个等级的日志：debug、 info、 notice、 warning、 error、 critical、 alert 以及 emergency


----------
