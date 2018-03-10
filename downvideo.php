<?php
    if(!is_array($_GET)&&count($_GET)>0){
        exit();
    }
    @error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    @ignore_user_abort(0);
    @set_time_limit(0);
    include('./YouTubeDownloader.php');
    $yt = new YouTubeDownloader();
    $u="https://www.youtube.com/watch?v=".$_GET['v'];
    $links = $yt->getDownloadLinks($u);
    switch ($_GET['quality'])
    {
    case "3GP144P":
        $file_path=$links['17']['url'];
        break;
    case "360P":
        $file_path=$links['18']['url'];
        break;
    case "720P":
        $file_path=$links['22']['url'];
        break;
    case "WebM360P":
        $file_path=$links['43']['url'];
        break;
    case "Unknown":
        $file_path=$links['36']['url'];
        break;
    }
    $url = trim($file_path);


    $urlArgs = parse_url($url);
 
    $host = $urlArgs['host'];
    $requestUri = $urlArgs['path'];
 
    if (isset($urlArgs['query'])) {
        $requestUri .= '?' . $urlArgs['query'];
    }
 
    $protocol = ($urlArgs['scheme'] == 'http') ? 'tcp' : 'ssl';
    $port = $urlArgs['port'];
 
 
 
 
 
    if (empty($port)) {
        $port = ($protocol == 'tcp') ? 80 : 443;
    }
 
    $header = "{$_SERVER['REQUEST_METHOD']} {$requestUri} HTTP/1.1\r\nHost: {$host}\r\n";
 
    unset($_SERVER['HTTP_HOST']);
    $_SERVER['HTTP_CONNECTION'] = 'close';
 
    if ($_SERVER['CONTENT_TYPE']) {
        $_SERVER['HTTP_CONTENT_TYPE'] = $_SERVER['CONTENT_TYPE'];
    }
 
    foreach ($_SERVER as $x => $v) {
        if (substr($x, 0, 5) !== 'HTTP_') {
            continue;
        }
        $x = strtr(ucwords(strtr(strtolower(substr($x, 5)), '_', ' ')), ' ', '-');
        $header .= "{$x}: {$v}\r\n";
    }
 
    $header .= "\r\n";
 
    $remote = "{$protocol}://{$host}:{$port}";
 
    $context = stream_context_create();
    stream_context_set_option($context, 'ssl', 'verify_host', false);
 
    $p = stream_socket_client($remote, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $context);
 
    if (!$p) {
        exit;
    }
 
    fwrite($p, $header);
 
    $pp = fopen('php://input', 'r');
 
    while ($pp && !feof($pp)) {
        fwrite($p, fread($pp, 1024));
    }
 
    fclose($pp);
 
    $header = '';
 
    $x = 0;
    $len = false;
    $off = 0;
 
    while (!feof($p)) {
        if ($x == 0) {
            $header .= fread($p, 1024);
 
            if (($i = strpos($header, "\r\n\r\n")) !== false) {
                $x = 1;
                $n = substr($header, $i + 4);
                $header = substr($header, 0, $i);
                $header = explode(PHP_EOL, $header);
                foreach ($header as $m) {
                    if (preg_match('!^\\s*content-length\\s*:!is', $m)) {
                        $len = trim(substr($m, 15));
                    }
                    
                    header($m);
                  }
                  $fname=$_GET['name'].'.'.$_GET['format'];
                  header("Content-Disposition: attachment;filename=\"$fname\"");
                  
                $off = strlen($n);
                echo $n;
                flush();
            }
        } else {
            if ($len !== false && $off >= $len) {
                break;
            }
            $n = fread($p, 1024);
            $off += strlen($n);
            echo $n;
            flush();
        }
    }
    
    fclose($p);
?>