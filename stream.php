<?php
@session_start();
@ob_start();
error_reporting(0);
@header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' ); 
@header( 'Date: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); 
@header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); 
@header( 'Cache-Control: private, max-age=1' ); 
@header("Pragma: no-cache");
@header("Content-Disposition: filename=".$_GET["id"].".mp4");

include('./YouTubeDownloader.php');
$yt = new YouTubeDownloader();
$u="https://www.youtube.com/watch?v=".$_GET['vv'];
$links = $yt->getDownloadLinks($u);
$file_path=$links['22']['url'];
function read_body(&$ch,&$string){
	global $loadedsize;
	$rtn=strlen($string);
	$loadedsize+=($rtn/1024);
	print($string);
	@ob_flush();
	@flush();
	if (0!=connection_status()) {
		curl_close($ch);
		exit();
	}
	@$string = NULL;
	//@unset($string);
	return $rtn;
}
function read_head(&$ch,&$header){
	
	if (!strpos($header,"Cache") && !strpos($header,"ocation") )
		@header(substr($header,0,strpos($header,"\r")));
    return strlen($header); 
}
		$header1 = array('Expect: ','Accept: */*');
		//$_SERVER['HTTP_RANGE'] = 'bytes=3902905-';
		if (isset($_SERVER['HTTP_RANGE'])) {
			$header1[] = 'Range: '.$_SERVER['HTTP_RANGE'];
			$header1[] = 'Referer: '.$file_path;
		}
		$header1[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $file_path);
			curl_setopt($ch, CURLOPT_TIMEOUT, 600);
			@curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			@curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $header1);
			curl_setopt($ch, CURLOPT_HEADERFUNCTION, "read_head");	//
			curl_setopt($ch, CURLOPT_WRITEFUNCTION, "read_body");	//
			//set_error_handler("customError");
			@ob_clean();
			curl_exec($ch);
	

?>