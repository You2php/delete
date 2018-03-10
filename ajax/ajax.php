<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//将出错信息输出到一个文本文件
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');  
    if(!is_array($_GET)&&count($_GET)<=0){
       exit();
    }
    include('../lib.php');
    $type=$_GET['type'];
    @$q=urlencode($_GET['q']);
    $ptk= isset($_GET['ptk']) ? $_GET['ptk'] : '';
    $order=isset($_GET['order'])?$_GET['order']:'relevance';
    $sortid=$_GET['sortid'];
    switch($type){
    	case 'video':
            	   $videodata=get_search_video($q,APIKEY,$ptk,'video',$order,GJ_CODE);
            	   	if($videodata['pageInfo']['totalResults']<=1){
    		    echo'<div class="alert alert-danger h4 p-3 m-2" role="alert">Entschuldigung, nicht gefunden<strong>'.urldecode($q).'</strong>Ähnliche Videos</div>';
    		    exit;
    		}
            	   echo '<ul  class="list-unstyled  video-list-thumbs row pt-1">';
            	   foreach($videodata["items"] as $v) {
                echo '<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4" ><a href="./watch.php?v='.$v["id"]["videoId"].'" target="_black" class="hhh" title="'.$v["snippet"]["title"].'" >
            			<img src="./thumbnail.php?type=mqdefault&vid='.$v["id"]["videoId"].'" class="img-responsive" />
            			<p class="fa fa-play-circle-o kkk" ></p>
            			<span class="text-dark text-overflow font2 my-2">'.$v["snippet"]["title"].'</span></a>
            		
            		<div class="pull-left pull-left1 icontext"><i class="fa fa-user icoys"></i><span class="pl-1"><a href="./channel.php?channelid='.$v["snippet"]["channelId"].'" class="icoys" title="'.$v["snippet"]["channelTitle"].'" >'.$v["snippet"]["channelTitle"].'</a>
            		</span></div>
            		
            		<div class="pull-right pull-right1 icontext">
            		    <i class="fa fa-clock-o pl-1 icoys "></i><span class="pl-1 icoys">'.format_date($v["snippet"]["publishedAt"]).'</span></div>
            </li>';
            }
                echo '</ul> ';
                echo '<div class="col-md-12">';
            if (array_key_exists("nextPageToken",$videodata) && array_key_exists("prevPageToken",$videodata) ) {
               
                echo'<a class="btn btn-outline-primary  w-25 pull-left" href="./search.php?q='.$_GET["q"].'&order='.$_GET["order"].'&type='.$_GET['type'].'&pageToken='.$videodata["prevPageToken"].'" data-toggle="">上一页</a>
                      <a class="btn btn-outline-primary  w-25 pull-right" href="./search.php?q='.$_GET["q"].'&order='.$_GET["order"].'&type='.$_GET['type'].'&pageToken='.$videodata["nextPageToken"].'" data-toggle="">下一页</a>
                    ';
            } elseif (array_key_exists("nextPageToken",$videodata) && !array_key_exists("prevPageToken",$videodata)) {
                echo '<a class="btn btn-outline-primary btn-block" href="./search.php?q='.$_GET["q"].'&order='.$_GET["order"].'&type='.$_GET['type'].'&pageToken='.$videodata["nextPageToken"].'" data-toggle="">下一页</a>
                    ';
            } elseif (!array_key_exists("nextPageToken",$videodata) && !array_key_exists("prevPageToken",$videodata)) {} else {
                echo '<a class="btn btn-outline-primary btn-block" href="./search.php?q='.$_GET["q"].'&order='.$_GET["order"].'&type='.$_GET['type'].'&pageToken='.$videodata["prevPageToken"].'" data-toggle="">上一页</a>' ;
            }
            echo'</div>';
    		break;
        case 'recommend':
    $random=random_recommend();
    foreach($random as $v) {
    echo '<span class="txt2 ricon h5">'.$v['t'].'</span>';
     echo'<ul class="list-unstyled video-list-thumbs row pt-1">';
        foreach ($v['dat'] as $value) {
          
    echo '<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4" ><a href="./watch.php?v='. $value['id'].'" class="hhh" >
    			<img src="./thumbnail.php?type=mqdefault&vid='.$value['id'].'" class=" img-responsive" /><p class="fa fa-play-circle-o kkk" ></p>
    			<span class="text-dark text-overflow font2 my-2" title="'.$value['title'].'">'.$value['title'].'</span></a>';
    		
        }
    echo '</ul>';
    		} 
      break;
    	case 'channel':
                  $videodata=get_search_video($q,APIKEY,$ptk,'channel',$order,GJ_CODE);
                  echo'<div class="row">';
            	   foreach($videodata['items'] as $v) {
            	    echo '<div class="col-md-6 col-sm-12 col-lg-6 col-xs-6 p-3 offset"><div class="media">
      <img class="col-4 d-flex align-self-center mr-3  mtpd" src="./thumbnail.php?type=photo&vid='.$v['snippet']['channelId'].'">
      <div class="media-body col-8 chaneelit">
        <a href="./channel.php?channelid='.$v['snippet']['channelId'].'" class="mtpda"><h5 class="mt-0">'.$v['snippet']['channelTitle'].'</h5></a>
        <p class="mb-0">'.$v['snippet']['description'].'</p>
      </div>
    </div></div>';
    }
    	            echo'</div>';
    	            echo '<div class="col-md-12 pt-3">';
            if (array_key_exists("nextPageToken",$videodata) && array_key_exists("prevPageToken",$videodata) ) {
               
                echo'<a class="btn btn-outline-primary  w-25 pull-left" href="./search.php?q='.$_GET["q"].'&order='.$_GET["order"].'&type='.$_GET['type'].'&pageToken='.$videodata["prevPageToken"].'" data-toggle="">上一页</a>
                      <a class="btn btn-outline-primary  w-25 pull-right" href="./search.php?q='.$_GET["q"].'&order='.$_GET["order"].'&type='.$_GET['type'].'&pageToken='.$videodata["nextPageToken"].'" data-toggle="">下一页</a>
                    ';
            } elseif (array_key_exists("nextPageToken",$videodata) && !array_key_exists("prevPageToken",$videodata)) {
                echo '<a class="btn btn-outline-primary btn-block" href="./search.php?q='.$_GET["q"].'&order='.$_GET["order"].'&type='.$_GET['type'].'&pageToken='.$videodata["nextPageToken"].'" data-toggle="">下一页</a>
                    ';
            } elseif (!array_key_exists("nextPageToken",$videodata) && !array_key_exists("prevPageToken",$videodata)) {} else {
                echo '<a class="btn btn-outline-primary btn-block" href="./search.php?q='.$_GET["q"].'&order='.$_GET["order"].'&type='.$_GET['type'].'&pageToken='.$videodata["prevPageToken"].'" data-toggle="">上一页</a>' ;
            }
            echo'</div>';
    		break;
    	case 'channels':
    		$video=get_channel_video($_GET['channelid'],$ptk,APIKEY,GJ_CODE);
    		if($video['pageInfo']['totalResults']<=1){
    		    echo'<p>Inhalte konnten nicht abgerufen werden! Dieser Kanalbenutzer hat keinen Inhalt hochgeladen oder der Kanalinhalt ist urheberrechtlich geschützt und kann derzeit nicht angezeigt werden!</p>';
    		    exit;
    		}
    		foreach($video['items'] as $v) {
        echo ' <div class="media height1 py-3 pt-3">
    		<div class="media-left" style="width:30%;min-width:30%;">
    		<a href="./watch.php?v='. $v['id']['videoId'].'" target="_blank" class="d-block" style="position:relative">
    		<img src="./thumbnail.php?type=mqdefault&vid='. $v['id']['videoId'].'" width="100%">
    		<p class="small smallp"><i class="fa fa-clock-o pr-1 text-white"></i>'.format_date($v['snippet']['publishedAt']).'</p>
    		</a>
    		</div>
    		<div class="media-body pl-2"  style="width:70%;max-width:70%;">
    			<h5 class="media-heading listfont">
    				<a href="./watch.php?v='. $v['id']['videoId'].'" target="_blank" class="font30" title="'.$v["snippet"]["title"].'">'.$v["snippet"]["title"].'</a>
    			</h5>
    			<p class="listfont1">'.$v['snippet']['description'].'</p>
    			
    		</div>
    	</div>';
     }
     
    
    if (array_key_exists("nextPageToken",$video) && array_key_exists("prevPageToken",$video) ) {
       
        echo'<a class="btn btn-outline-primary m-1 w-25 pull-left" href="./channel.php?channelid='.$_GET['channelid'].'&pageToken='.$video['prevPageToken'].'" data-toggle="">上一页</a>
              <a class="btn btn-outline-primary m-1 w-25 pull-right" href="./channel.php?channelid='.$_GET['channelid'].'&pageToken='.$video['nextPageToken'].'" data-toggle="">下一页</a>
            ';
    } elseif (array_key_exists("nextPageToken",$video) && !array_key_exists("prevPageToken",$video)) {
        echo '<a class="btn btn-outline-primary m-1 btn-block" href="./channel.php?channelid='.$_GET['channelid'].'&pageToken='.$video['nextPageToken'].'" data-toggle="">下一页</a>
            ';
    } elseif (!array_key_exists("nextPageToken",$video) && !array_key_exists("prevPageToken",$video)) {} else {
        echo '<a class="btn btn-outline-primary m-1 btn-block" href="./channel.php?channelid='.$_GET['channelid'].'&pageToken='.$video['prevPageToken'].'" data-toggle="">上一页</a>' ;
    }
    echo'</div>';
    break;
    	case 'related':
    	 $related=get_related_video($_GET['v'],APIKEY);
    	 
     foreach($related["items"] as $v) {
       echo'<div class="media height1">
    		<div class="media-left" style="width:40%">
    		<a href="./watch.php?v='.$v["id"]["videoId"].'" >
    		<img src="./thumbnail.php?type=mqdefault&vid='.$v["id"]["videoId"].'" width="100%">
    		</a>
    		</div>
    		<div class="media-body pl-2">
    			<h5 class="media-heading height2">
    				<a href="./watch.php?v='.$v["id"]["videoId"].'" class="text-dark">'.$v["snippet"]["title"].'</a>
    			</h5>
    			<p class="small mb-0 pt-2">'
    			.format_date($v["snippet"]["publishedAt"]).
    			'</p>
    		</div>
    	</div>';  
     }	
    		break;
    case 'menu':
        $vica=videoCategories(APIKEY,GJ_CODE);
        
        echo '<ul class="list-group text-dark">
        <li class="list-group-item font-weight-bold"><i class="fa fa-home fa-fw pr-4"></i><a href="./" class="text-dark">Startseite</a></li>
        <li class="list-group-item"><i class="fa fa-fire fa-fw pr-4"></i><a href="./content.php?cont=trending" class="text-dark">Trends</a></li>
        <li class="list-group-item"><i class="fa fa-history fa-fw pr-4"></i><a href="./content.php?cont=history" class="text-dark">Verlauf</a></li>
        <li class="list-group-item"><i class="fa fa-gavel fa-fw pr-4"></i><a href="./content.php?cont=DMCA"class="text-dark">DMCA</a></li>
        <li class="list-group-item"><i class="fa fa-cloud-download fa-fw pr-4"></i><a href="./content.php?cont=video" class="text-dark">Downloader</a></li>
        <li class="list-group-item"><i class="fa fa-file-code-o fa-fw pr-4 pr-4"></i><a href="./content.php?cont=api" class="text-dark">API</a></li>
        ';
        break;
    
    case 'trending':
    $home_data=get_trending(APIKEY,'18','',GJ_CODE);
    echo'<ul class="list-unstyled video-list-thumbs row pt-1">';
    foreach($home_data["items"] as $v) {
    echo '<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4" ><a href="./watch.php?v='. $v["id"].'" class="hhh" >
    			<img src="./thumbnail.php?type=mqdefault&vid='.$v["id"].'" class=" img-responsive" /><p class="fa fa-play-circle-o kkk" ></p>
    			<span class="text-dark text-overflow font2 my-2" title="'.$v["snippet"]["title"].'">'.$v["snippet"]["title"].'</span></a>
    			<div class="pull-left pull-left1 icontext"><i class="fa fa-user icoys"></i><span class="pl-1"><a href="./channel.php?channelid='.$v["snippet"]["channelId"].'"  class=" icoys" title="'.$v["snippet"]["channelTitle"].'">'.$v["snippet"]["channelTitle"].'</a></span></div>
    		
    		<div class="pull-right pull-right1 icontext icoys">
    		    <i class="fa fa-clock-o pl-1"></i><span class="pl-1">'.format_date($v["snippet"]["publishedAt"]).'</span></div>
    		<span class="duration">'.covtime($v["contentDetails"]["duration"]).'</span></li>';
    		}  
    echo '</ul>';
      break;
    
    
      
    case 'DMCA':
        echo '<div class="font-weight-bold h6 pb-1">DMCA</div>';
        echo '<h6><b>DMCA：</b><h6>';
        echo '<p class="h6" style="line-height: 1.7">Diese Website bezieht Videoinhalte aus dem Internet.<br>
Wenn wir versehentlich gegen Ihr Urheberrecht verstoßen.<br>
Schicken Sie Urheberrechtsbeschwerden an '.EMAIL.'! Wir antworten innerhalb von 48 Stunden!<br></p>';
echo '<h6 class="pt-3"><b>Benutzerhinweis:</b><h6>';
        echo '<p class="h6" style="line-height: 1.7">Bitte lesen Sie die folgenden Bedingungen sorgfältig durch: Wenn Sie mit den Bedingungen dieser Vereinbarung nicht einverstanden sind, können Sie diese Website nicht nutzen. Wenn Sie diese Website besuchen, unabhängig davon, ob Sie im Internet surfen oder unabsichtlich surfen möchten, akzeptieren Sie alle Bedingungen dieser Vereinbarung. <br>
        1. Die Seite stellt mithilfe eines automatischen Abrufverfahrens Inhalte aus Youtube dar. Die Seite ist für den legitimen Inhalt nicht verantwortlich und übernimmt keine gesetzliche Haftung. <br>
        2. Alle Inhalte von Drittanbietern, filtert die Seite mit Maskentechniken, um rechtswidrige Inhalte zu verringern. Wenn Sie dennoch einen rechtswidrigen Inhalt entdecken sollten, bitten wir darum diesen bei uns zu melden. <br>
		</p> ';
        echo '<h6 class="pt-3"><b>Haftungsausschluss:</b><h6>';
        echo '<p class="h6" style="line-height: 1.7">1. Diese Website kann nicht garantieren, dass der Inhalt von Websites Dritter, die von uns indexiert wurden, korrekt ist.<br>
        2.Der Inhalt, der von einer Person oder Organisation auf einer Website eines Dritten veröffentlicht wird, stellt nur ihre persönliche Position und Sichtweise dar. Diese Seite wird nur als Suchwerkzeug verwendet und repräsentiert nicht die Position oder den Standpunkt dieser Seite. Die Betreiber dieser Seite, ausgenommen der Inhalt, sind nicht verantwortlich für den Inhalt der Websites Dritter, da die Inhalte der Websites Dritter von allen Inhalten betroffen sind. Diese Seite übernimmt keine rechtliche und gemeinschaftliche Haftung.<br>
         
         </p>';
       break;
     case 'api':
         echo '<div class="font-weight-bold h6 pb-1">API</div>';
         echo '<p>Schnittstellenadresse :</p>
         <div class="alert table-inverse" role="alert">'.dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/api.php</div><p>Methode anfordern : GET</p><table class="table table-bordered table-active"><thead><tr><th>Parametername</th><th>Parameterbeschreibung</th></tr> </thead><tbody><tr><td>type</td><td>Anforderungstyp (erhalten Sie Videoinformationen, wenn der Parameter "info" ist, erhalten Sie einen Video-Download-Link, wenn der Parameter "downlink" lautet)</td></tr><tr><td>v</td><td>Youtube-ID</td></tr></tbody></table>'
               ;
         echo '<h5>Holen Sie sich Video-Informationen: (Video-Inhalte, Video-Einführung, Schöpfer, etc.)</h5>';
         echo '<p>Beispiel anfordern:'.dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/api.php?type=info&v=LsDwn06bwjM</p>
               <p>Rückgabewert: JSON</p>';
         
         echo '<h5>Link zum Herunterladen der Videoquelle:</h5>';
         echo '<p>Beispiel anfordern:'.dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/api.php?type=downlink&v=LsDwn06bwjM</p>
               <p>Rückgabewert: JSON</p>';
         break;
    case 'videos':
        echo '<div class="font-weight-bold h6 pb-1">Downloader</div>';
        echo '<form  onsubmit="return false" id="ipt">
  <div class="form-group text-center" >
  <input name="type" value="Downloader" style="display: none;">
      <input type="text" name="link"  placeholder="Bitte geben Sie den Youtube Video Link ein" id="soinpt"  autocomplete="off" /><button type="submit" id="subu" style="width: 24%;vertical-align:middle;border: none;height: 50px;background-color: #e62117;color: #fff;font-size: 18px;display: inline-block;" ><i class="fa fa-download fa-lg pr-1"></i>Herunterladen</button>
  </div>
    </form>';
    if(isset($_GET['type']) && isset($_GET['v'])){
        echo '<div id="videoslist" class="text-center">';
       $viddata=get_video_info($_GET['v'],APIKEY);
        echo '<h5>'.$viddata['items']['0']['snippet']['title'].'</h5>';
        echo '<div class="p-3"><img src="./thumbnail.php?type=0&vid='.$_GET['v'].'" class="rounded img-fluid"></div>';
        echo video_down($_GET['v'],$viddata['items']['0']['snippet']['title']);  
        echo '</div>';
    }else{
        echo '<div id="videoslist" class="text-center"><p>Tipp: Wenn Sie nichts herunterladen können, versuchen Sie mit der rechten Maustaste auf Speichern unter das Video herunterzuladen!<p></div>'; 
    }
    echo '<script>
     $("#subu").click(function() {$("#videoslist").load(\'./ajax/ajax.php\',$("#ipt").serialize());});
 </script>
';
       break;
       
       
    case 'trendinglist':
    $home=get_trending(APIKEY,'48',$ptk,GJ_CODE);
        echo '<div class="font-weight-bold h6 pb-1">Trends</div> ';
    echo'<ul class="list-unstyled video-list-thumbs row pt-1">';
    foreach($home["items"] as $v) {
    echo '<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4" ><a href="./watch.php?v='. $v["id"].'" class="hhh" >
    			<img src="./thumbnail.php?type=mqdefault&vid='.$v["id"].'" class=" img-responsive" /><p class="fa fa-play-circle-o kkk" ></p>
    			<span class="text-dark text-overflow font2 my-2">'.$v["snippet"]["title"].'</span></a>
    			<div class="pull-left pull-left1 icontext"><i class="fa fa-user icoys"></i><span class="pl-1"><a href="./channel.php?channelid='.$v["snippet"]["channelId"].'"  class="icoys">'.$v["snippet"]["channelTitle"].'</a></span></div>
    		
    		<div class="pull-right pull-right1 icoys icontext">
    		    <i class="fa fa-clock-o"></i><span class="pl-1">'.format_date($v["snippet"]["publishedAt"]).'</span>
    		</div>
    		<span class="duration">'.covtime($v["contentDetails"]["duration"]).'</span>
    		</li>';
    		}  
    echo '</ul>';
    if (array_key_exists("nextPageToken",$home) && array_key_exists("prevPageToken",$home) ) {
       
        echo'<a class="btn btn-outline-primary m-1 w-25 pull-left" href="./content.php?cont=trending&pageToken='.$home['prevPageToken'].'" data-toggle="">Vorherige Seite</a>
              <a class="btn btn-outline-primary m-1 w-25 pull-right" href="./content.php?cont=trending&pageToken='.$home['nextPageToken'].'" data-toggle="">Nächste Seite</a>
            ';
    } elseif (array_key_exists("nextPageToken",$home) && !array_key_exists("prevPageToken",$home)) {
        echo '<a class="btn btn-outline-primary m-1 btn-block" href="./content.php?cont=trending&pageToken='.$home['nextPageToken'].'" data-toggle="">Nächste Seite</a>
            ';
    } elseif (!array_key_exists("nextPageToken",$home) && !array_key_exists("prevPageToken",$home)) {} else {
        echo '<a class="btn btn-outline-primary m-1 btn-block" href="./content.php?cont=trending&pageToken='.$home['prevPageToken'].'" data-toggle="">Vorherige Seite</a>' ;
    }
    break;
    
    
    
    case 'history':
    $hisdata=Hislist($_COOKIE['history'],APIKEY);
    echo '<div class="font-weight-bold h6 pb-1">Verlauf</div> ';
       if($hisdata['pageInfo']['totalResults'] ==0){echo '<div class="alert alert-warning" role="alert"><h4 class="alert-heading">Verlauf</h4>
  <p>Entschuldigung! Du hast noch keine Videos gesehen！</p>
  <p class="mb-0">Diese Seite verwendet Cookies, um Ihren Verlauf vorübergehend in Ihrem Browser zu speichern.Diese Seite speichert nicht Ihren Anzeigeverlauf.Es speichert nur Ihre letzten 30 Browserverlauf.Wenn Sie Ihre Browser-Cookies bereinigen, werden Sie Kann nicht wiederhergestellt werden！</p>
</div>';exit();}           
                foreach($hisdata["items"] as $v) {
                $description = strlen($v['snippet']['description']) > 250 ? substr($v['snippet']['description'],0,250)."...." : $v['snippet']['description'];
                echo '<div class="media height1 py-3 pt-3 ">
    		<div class="media-left" style="width:30%;min-width:30%;">
    		<a href="./watch.php?v='.$v['id'].'" target="_blank" class="d-block" style="position:relative">
    		<img src="./thumbnail.php?type=mqdefault&vid='.$v["id"].'" width="100%">
    		<p class="small smallp"><i class="fa fa-clock-o pr-1 text-white"></i>'.covtime($v['contentDetails']['duration']).'</p>
    		</a>
    		</div>
    		<div class="media-body pl-2"  style="width:70%;max-width:70%;">
    			<h5 class="media-heading listfont">
    				<a href="./watch.php?v='.$v['id'].'" target="_blank" class="font30">'.$v["snippet"]["title"].'</a>
    			</h5>
    			<p class="listfont1">'.$description.'</p>
    			
    		</div> 
    		</div>';    
                    
                } 
     break;
     
     
    case 'Videodownloader': 
        if(stripos($_GET['link'],'youtu.be') !== false || stripos($_GET['link'],'youtube.com') !== false || stripos($_GET['link'],'watch?v=') !== false  ){}else{echo '<h6>Illegale Anfrage</h6>';break;exit();}
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $_GET['link'], $mats);
        $viddata=get_video_info($mats[1],APIKEY);
        echo '<h5>'.$viddata['items']['0']['snippet']['title'].'</h5>';
        echo '<div class="text-center p-3"><img src="./thumbnail.php?type=0&vid='.$mats[1].'" class="rounded img-fluid"></div>';
        echo video_down($mats[1],$viddata['items']['0']['snippet']['title']);
     break;
     
    case 'category':   
    $category=Categories($sortid,APIKEY,$ptk,$order,GJ_CODE);
    if($category['pageInfo']['totalResults']=='0'){
        echo '<div class="alert alert-danger m-2" role="alert">
                <strong>Entschuldigung! </strong> Dieser Inhalt ist aufgrund von Urheberrechtseinschränkungen nicht sichtbar!
              </div>';
              exit();
    }
    echo'<ul class="list-unstyled video-list-thumbs row pt-1">';
    foreach($category['items'] as $v) {
    echo '<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4" ><a href="./watch.php?v='. $v['id']['videoId'].'" class="hhh" >
    			<img src="./thumbnail.php?type=mqdefault&vid='.$v['id']['videoId'].'" class=" img-responsive" /><p class="fa fa-play-circle-o kkk" ></p>
    			<span class="text-dark text-overflow font2 my-2">'.$v['snippet']['title'].'</span></a>
    			<div class="pull-left pull-left1 icontext"><i class="fa fa-user"></i><span class="pl-1 icoys"><a href="./channel.php?channelid='.$v['snippet']['channelId'].'" class="icoys">'.$v['snippet']['channelTitle'].'</a></span></div>
    		
    		<div class="pull-right pull-right1 icontext icoys">
    		<i class="fa fa-clock-o pl-1"></i><span class="pl-1">'.format_date($v["snippet"]["publishedAt"]).'</span>
            </div>
    		';
    		}  
    echo '</ul>';
    if (array_key_exists("nextPageToken",$category) && array_key_exists("prevPageToken",$category) ) {
       
        echo'<a class="btn btn-outline-primary m-1 w-25 pull-left" href="./content.php?cont=category&sortid='.$sortid.'&order='.$_GET["order"].'&pageToken='.$category['prevPageToken'].'" data-toggle="">上一页</a>
              <a class="btn btn-outline-primary m-1 w-25 pull-right" href="./content.php?cont=category&sortid='.$sortid.'&order='.$_GET["order"].'&pageToken='.$category['nextPageToken'].'" data-toggle="">下一页</a>
            ';
    } elseif (array_key_exists("nextPageToken",$category) && !array_key_exists("prevPageToken",$category)) {
        echo '<a class="btn btn-outline-primary m-1 btn-block" href="./content.php?cont=category&sortid='.$sortid.'&order='.$_GET["order"].'&pageToken='.$category['nextPageToken'].'" data-toggle="">下一页</a>
            ';
    } elseif (!array_key_exists("nextPageToken",$category) && !array_key_exists("prevPageToken",$category)) {} else {
        echo '<a class="btn btn-outline-primary m-1 btn-block" href="./content.php?cont=category&sortid='.$sortid.'&order='.$_GET["order"].'&pageToken='.$category['prevPageToken'].'" data-toggle="">上一页</a>' ;
    }
    
    break;    
    }
    
?>