<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Nach Updates suchen</title>
    </head>
    <body>
        <?php
        $version='4.0';
        $Posttime='20171225';
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL ,'https://raw.githubusercontent.com/zxq2233/You2php.github.io/master/version.json');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,20);
        curl_setopt($ch,CURLOPT_REFERER ,'http://www.youtube.com/');
        curl_setopt($ch,CURLOPT_USERAGENT ,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.91 Safari/534.30");
        $f=curl_exec($ch);
        curl_close($ch);
        $up=json_decode($f,true);
        if ( (int)$up['time'] > (int)$Posttime ) {
            
            echo 'Dieses Programm hat eine neue Version, bitte upgraden!</br>';
           echo 'Aktuelle Version：v'.$version.'</br>';
           echo 'Die neueste Version：v'.$up['version'].'</br>';
           echo 'Bitte laden Sie die neueste Version von dieser Adresse herunter：<a href="'.$up['links'].'" target="_blank">'.$up['links'].'</a></br>';
           echo 'Inhalt aktualisieren：'.$up['des'];
        } else{
          echo 'Dieses Programm ist bereits die neueste Version, kein Upgrade erforderlich！'; 
        }
        ?>    
</body>
</html>