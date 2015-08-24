<?php
/**
 * Created by PhpStorm.
 * User: flowerZhou
 * Date: 2015/8/24
 * Time: 18:16
 *
 * 抓取页面信息：php程序模拟浏览器
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



//fsockopen
$url = 'www.baidu.com';
$fp = fsockopen($url, 443, $errno, $errstr, 30);
if(!$fp)
{
    echo $errstr.':'.$errno;
}
else
{
    $out = "GET / HTTP/1.1\n\n";
    fwrite($fp, $out);
    while(!feof($fp))
    {
        echo fgets($fp, 128);
    }
    fclose($fp);
}