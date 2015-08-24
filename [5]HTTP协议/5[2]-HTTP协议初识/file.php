<?php
/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2015/8/24
 * Time: 18:16
 *
 * 抓取页面信息：php程序-模拟浏览器-请求数据
 */

//file
//$url = 'https://www.baidu.com';
//$result_array = file($url);
//$result = implode('', $result_array);
//print_r($result);



//file_get_contents()
//$url = 'https://www.baidu.com';
//$result_array = file_get_contents($url);
//print_r($result_array);


//readfile()
//$result = readfile('www.baidu.com');   //报错：Warning: readfile(www.baidu.com): failed to open stream
$result = readfile('https://www.baidu.com');  //正确
print_r($result);


//curl方法



//fsockopen
//$url = 'http://www.qq.com/';
//$fp = fsockopen($url, 8080, $errno, $errstr, 30);
//if(!$fp)
//{
//    echo $errstr.':'.$errno;
//}
//else
//{
//    $out = "GET / HTTP/1.0\n\n";
//    fwrite($fp, $out);
//    while(!feof($fp))
//    {
//        echo fgets($fp, 128);
//    }
//    fclose($fp);
//}