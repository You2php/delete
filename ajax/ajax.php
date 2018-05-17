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
    		    echo'<div class="alert alert-danger h4 p-3 m-2" role="alert">抱歉，没有找到与<strong>'.urldecode($q).'</strong>相关的视频。</div>';
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
    		    echo'<p>获取内容失败！此频道用户没有上传任何内容，或者频道内容受版权保护,暂时无法查看！</p>';
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
        <li class="list-group-item font-weight-bold"><i class="fa fa-home fa-fw pr-4"></i><a href="./" class="text-dark">首页</a></li>
        <li class="list-group-item"><i class="fa fa-fire fa-fw pr-4"></i><a href="./content.php?cont=trending" class="text-dark">时下流行</a></li>
        <li class="list-group-item"><i class="fa fa-history fa-fw pr-4"></i><a href="./content.php?cont=history" class="text-dark">历史记录</a></li>
        <li class="list-group-item"><i class="fa fa-gavel fa-fw pr-4"></i><a href="./content.php?cont=DMCA"class="text-dark">DMCA</a></li>
        <li class="list-group-item"><i class="fa fa-cloud-download fa-fw pr-4"></i><a href="./content.php?cont=video" class="text-dark">视频下载</a></li>
        <li class="list-group-item"><i class="fa fa-file-code-o fa-fw pr-4 pr-4"></i><a href="./content.php?cont=api" class="text-dark">API</a></li>
        </ul>
        <ul class="list-group pt-3">
        <li class="list-group-item font-weight-bold"></i>YOUTUBE 精选</li>
        ';
        foreach($vica as $v){
        echo '<li class="list-group-item"><a href="./content.php?cont=category&sortid='.$v['id'].'" class="text-dark">'.$v['snippet']['title'].'</a></li>';    
        }
        echo '</ul>';
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
        echo '<div class="font-weight-bold h6 pb-1">DMCA及免责声明</div>';
        echo '<h6><b>DMCA：</b><h6>';
        echo '<p class="h6" style="line-height: 1.7">This site video content from the Internet.<br>
If inadvertently violate your copyright.<br>
Send copyright complaints to '.EMAIL.'! We will response within 48 hours!<br></p>';
echo '<h6 class="pt-3"><b>用户须知：</b><h6>';
        echo '<p class="h6" style="line-height: 1.7">请您仔细阅读以下条款，如果您对本协议的任何条款表示异议，您可以选择不使用本网站。一旦您浏览本站，无论您是有意浏览还是无意浏览，均意味着您完全接受本协议项下的全部条款。<br>
        1.鉴于本站以非人工检索方式、您请求的内容版权归第三方站点内容，您可能从该第本站网页上获得资讯及享用服务，但本站不对其内容合法性负责，亦不承担任何法律责任。<br>
        2.本站所有内容来源自第三方站点，本站将以技术手段最大限度的过滤屏蔽不良违法内容，若您无意之中浏览到了这些内容请立即关闭。<br>
        3.使用本站，用户需承诺不得以任何方式利用本站内容直接或间接从事违反中国法律以及社会公德的行为，本站有权对违反上述承诺的内容予以删除。<br>
        4.任何个人或组织不得利用本站内容制作、上载、复制、发布、传播或者转载如下内容：反对宪法所确定的基本原则的；危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；损害国家荣誉和利益的；煽动民族仇恨、民族歧视，破坏民族团结的；破坏国家宗教政策，宣扬邪教和封建迷信的；散布谣言，扰乱社会秩序，破坏社会稳定的；散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪；侮辱或者诽谤他人，侵害他人合法权益的；含有法律、行政法规禁止的其他内容的信息。<br></p>';
        echo '<h6 class="pt-3"><b>免责声明：</b><h6>';
         echo '<p class="h6" style="line-height: 1.7">1.本站不能对我们索引的第三方网站内容的正确性进行保证。<br>
         2.任何个人或组织在第三方网站发表的内容仅表明其个人的立场和观点，本站仅作为检索工具，并不代表本站的立场或观点。本站非内容的创作者，不对第三方网站内容负责，因第三方网站内容引发的一切纠纷，由该内容的创作者承担全部法律及连带责任。本站不承担任何法律及连带责任。<br>
         
         </p>';
       break;
     case 'api':
         echo '<div class="font-weight-bold h6 pb-1">API</div>';
         echo '<p>接口地址 :</p>
         <div class="alert table-inverse" role="alert">'.dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/api.php</div><p>请求方法 : GET</p><table class="table table-bordered table-active"><thead><tr><th>参数名</th><th>参数描述</th></tr> </thead><tbody><tr><td>type</td><td>请求类型(参数为info时获取视频信息，参数为downlink时获取视频下载链接)</td></tr><tr><td>v</td><td>Youtube视频ID</td></tr></tbody></table>'
               ;
         echo '<h5>获取视频信息：(视频内容、视频简介，创作者等信息)</h5>';
         echo '<p>请求示例：'.dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/api.php?type=info&v=LsDwn06bwjM</p>
               <p>返回值： JSON</p>';
         
         echo '<h5>获取视频源下载链接:</h5>';
         echo '<p>请求示例：'.dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/api.php?type=downlink&v=LsDwn06bwjM</p>
               <p>返回值： JSON</p>';
         break;
    case 'videos':
        echo '<div class="font-weight-bold h6 pb-1">视频下载</div>';
        echo '<form  onsubmit="return false" id="ipt">
  <div class="form-group text-center" >
  <input name="type" value="videodownload" style="display: none;">
      <input type="text" name="link"  placeholder="请输入Youtube视频链接" id="soinpt"  autocomplete="off" /><button type="submit" id="subu" style="width: 24%;vertical-align:middle;border: none;height: 50px;background-color: #e62117;color: #fff;font-size: 18px;display: inline-block;" ><i class="fa fa-download fa-lg pr-1"></i>下载</button>
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
        echo '<div id="videoslist" class="text-center"><p>提示:如果无法下载,请选择右键另存为!<p></div>'; 
    }
    echo '<script>
     $("#subu").click(function() {$("#videoslist").load(\'./ajax/ajax.php\',$("#ipt").serialize());});
 </script>
';
       break;
       
       
    case 'trendinglist':
    $home=get_trending(APIKEY,'48',$ptk,GJ_CODE);
        echo '<div class="font-weight-bold h6 pb-1">时下流行</div> ';
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
       
        echo'<a class="btn btn-outline-primary m-1 w-25 pull-left" href="./content.php?cont=trending&pageToken='.$home['prevPageToken'].'" data-toggle="">上一页</a>
              <a class="btn btn-outline-primary m-1 w-25 pull-right" href="./content.php?cont=trending&pageToken='.$home['nextPageToken'].'" data-toggle="">下一页</a>
            ';
    } elseif (array_key_exists("nextPageToken",$home) && !array_key_exists("prevPageToken",$home)) {
        echo '<a class="btn btn-outline-primary m-1 btn-block" href="./content.php?cont=trending&pageToken='.$home['nextPageToken'].'" data-toggle="">下一页</a>
            ';
    } elseif (!array_key_exists("nextPageToken",$home) && !array_key_exists("prevPageToken",$home)) {} else {
        echo '<a class="btn btn-outline-primary m-1 btn-block" href="./content.php?cont=trending&pageToken='.$home['prevPageToken'].'" data-toggle="">上一页</a>' ;
    }
    break;
    
    
    
    case 'history':
    $hisdata=Hislist($_COOKIE['history'],APIKEY);
    echo '<div class="font-weight-bold h6 pb-1">历史记录</div> ';
       if($hisdata['pageInfo']['totalResults'] ==0){echo '<div class="alert alert-warning" role="alert"><h4 class="alert-heading">历史记录</h4>
  <p>抱歉！您还没有观看过任何视频！</p>
  <p class="mb-0">本站使用cookies临时存储您的历史记录在您的浏览器上，本站不会对您的观看历史进行保存，仅记录您的最后30条浏览记录，若您清理过你的浏览器cookies，将无法恢复！</p>
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
     
     
    case 'videodownload': 
        if(stripos($_GET['link'],'youtu.be') !== false || stripos($_GET['link'],'youtube.com') !== false || stripos($_GET['link'],'watch?v=') !== false  ){}else{echo '<h6>非法请求</h6>';break;exit();}
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
                <strong>抱歉！</strong> 因版权方限制，本类内容暂时不提供浏览!
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
