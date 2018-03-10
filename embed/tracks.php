<?php
    //字幕代理
    include('../lib.php');
    $slink='https://www.youtube.com/api/timedtext?fmt=vtt&v='.$_GET['vtt'].'&lang='.$_GET['lang'];
    echo get_data($slink);
?>
 
