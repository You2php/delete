<?php
if(is_array($_GET)&&count($_GET)>0&&isset($_GET["q"])){
    $q=$_SERVER["QUERY_STRING"];
    }else{
    exit();
    }

$url='https://suggestqueries.google.com/complete/search?hl=zh-CN&ds=yt&client=youtube&hjson=t&cp=1&'.$q;
if (!function_exists("curl_init")) {
		$f = file_get_contents($url);
	} else {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.youtube.com/');
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.91 Safari/534.30");
		$f = curl_exec($ch);
		curl_close($ch);
	}
	echo $f;
?>