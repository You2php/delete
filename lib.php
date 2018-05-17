<?php
/**
 * Youtube Proxy 
 * Simple Youtube PHP Proxy Server
 * @author ZXQ
 * @version V1.2
 * @description 核心操作函数集合
 */

require_once(dirname(__FILE__).'/config.php');

//加载第三方ytb解析库
require_once(dirname(__FILE__).'/YouTubeDownloader.php');
//获取远程数据函数
 function get_data($url){
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
   return $f;  
}
//获取类别热门视频
function get_trending($apikey,$max,$pageToken='',$regionCode='vn'){
    $apilink='https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&chart=mostPopular&regionCode='.$regionCode.'&maxResults='.$max.'&key='.$apikey.'&pageToken='.$pageToken;
     return json_decode(get_data($apilink),true);
}

//获取视频数据函数
 function get_video_info($id,$apikey){
    $apilink='https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails,statistics&id='.$id.'&key='.$apikey;
     return json_decode(get_data($apilink),true);
}

//获取用户频道数据
function get_channel_info($cid,$apikey){
   $apilink='https://www.googleapis.com/youtube/v3/channels?part=snippet,contentDetails,statistics&hl=zh&id='.$cid.'&key='.$apikey;
   return json_decode(get_data($apilink),true);
}

//获取相关视频
function get_related_video($vid,$apikey){
   $apilink='https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=24&relatedToVideoId='.$vid.'&key='.$apikey;
   return json_decode(get_data($apilink),true);
}


//获取用户频道视频
function get_channel_video($cid,$pageToken='',$apikey,$regionCode='VN'){
   $apilink='https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&maxResults=50&type=video&regionCode='.$regionCode.'&hl=zh-CN&channelId='.$cid.'&key='.$apikey.'&pageToken='.$pageToken;
   return json_decode(get_data($apilink),true);
}

//获取视频类别内容
function videoCategories($apikey,$regionCode='HK'){
   $apicache = '/tmp/ytb_videoCategories_'.$regionCode;
   $json = file_get_contents($apicache);
   if (empty($json)) {
       $apilink='https://www.googleapis.com/youtube/v3/videoCategories?part=snippet&regionCode='.$regionCode.'&hl=zh-CN&key='.$apikey;
       $json = get_data($apilink);
      file_put_contents($apicache,$json);
      file_put_contents($apicache.".ts","REQUEST_TIME: " . $_SERVER['REQUEST_TIME']);
   }
   $ret = json_decode($json,true);
   $items = $ret['items'];
   if (strtolower($regionCode) == 'tw') {
      return array_filter($items, function($v){
         return array_search($v['id'], ['18','33','41','42']) === FALSE;
      });
   }
   return $items;
}


function categorieslist($id){
   $data=array(
    '1' => '电影和动画',
    '2' => '汽车',
    '10' => '音乐',
    '15' => '宠物和动物',
    '17' => '体育',
    '18' => '短片',
    '19' => '旅游和活动',
    '20' => '游戏',
    '21' => '视频博客',
    '22' => '人物和博客',
    '23' => '喜剧',
    '24' => '娱乐',
    '25' => '新闻和政治',
    '26' => 'DIY 和生活百科',
    '27' => '教育',
    '28' => '科学和技术',
    '30' => '电影',
    '31' => '动漫/动画',
    '32' => '动作/冒险',
    '33' => '经典',
    '34' => '喜剧',
    '35' => '纪录片',
    '36' => '剧情片',
    '37' => '家庭片',
    '38' => '外国',
    '39' => '恐怖片',
    '40' => '科幻/幻想',
    '41' => '惊悚片',
    '42' => '短片',
    '43' => '节目',
    '44' => '预告片'
       );
     if($id=='all'){
     return $data;    
     }else{
      return $data[$id];   
     }
}
//获取视频类别内容
function Categories($id,$apikey,$pageToken='',$order='relevance',$regionCode='VN'){
   $apilink='https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&&regionCode='.$regionCode.'&hl=zh-ZH&maxResults=48&videoCategoryId='.$id.'&key='.$apikey.'&order='.$order.'&pageToken='.$pageToken;
   return json_decode(get_data($apilink),true);
}


//获取搜索数据
function get_search_video($query,$apikey,$pageToken='',$type='video',$order='relevance',$regionCode='VN'){
   $apilink='https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=48&order='.$order.'&type='.$type.'&q='.$query.'&key='.$apikey.'&pageToken='.$pageToken;
   return json_decode(get_data($apilink),true);
}

//api返回值时间转换函数1
function covtime($youtube_time){
    $start = new DateTime('@0'); 
    $start->add(new DateInterval($youtube_time));
    if(strlen($youtube_time)<=7){
      return $start->format('i:s');  
    }else{
     return $start->format('H:i:s');   
    }
    
}   

//转换时间函数，计算发布时间几天前几年前
function format_date($time){
    $t=strtotime($time);
    $t=time()-$t;
    $f=array(
    '31536000'=>'年',
    '2592000'=>'个月',
    '604800'=>'周',
    '86400'=>'天',
    '3600'=>'小时',
    '60'=>'分钟',
    '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
}

//api返回值时间转换函数2
function str2time($ts) {
 return date("Y-m-d H:i", strtotime($ts));
}

 //播放量转换
function convertviewCount($value){
    if($value <= 10000){
    $number = $value;   
    }else{
      $number = $value / 1000 ; 
      $number = round($number,1).'K';
      
    }
    
    return $number;
}
//获取banner背景
function get_banner($a,$apikey){
   $apilink='https://www.googleapis.com/youtube/v3/channels?part=brandingSettings&id='.$a.'&key='.$apikey;
   $json=json_decode(get_data($apilink),true);
  if (array_key_exists('bannerTabletImageUrl',$json['items'][0]['brandingSettings']['image'])){
  return $json['items'][0]['brandingSettings']['image']['bannerTabletImageUrl'];    
 }else{
  return 'https://c1.staticflickr.com/5/4546/24706755178_66c375d5ba_h.jpg';   
 }
}
$videotype=array(
    '3GP144P' => array('3GP','144P','3gpp'),
    '360P' => array('MP4','360P','mp4'), 
    '720P' => array('MP4','720P','mp4'), 
    'WebM360P' => array('webM','360P','webm'), 
    'Unknown' => array('N/A','N/A','3gpp'), 
    );
    
//获取相关频道 api不支持，靠采集完成
require_once(dirname(__FILE__).'/inc/phpQuery.php');
require_once(dirname(__FILE__).'/inc/QueryList.php');
use QL\QueryList;
function get_related_channel($id){
    $channel='https://www.youtube.com/channel/'.$id;
    $rules = array(
    'id' => array('.branded-page-related-channels .branded-page-related-channels-list li','data-external-id'),
    'name' => array('.branded-page-related-channels .branded-page-related-channels-list li .yt-lockup .yt-lockup-content .yt-lockup-title a','text'),
);

return $data = QueryList::Query(get_data($channel),$rules)->data;
}

//采集抓取随机推荐内容
function random_recommend(){
   $dat=get_data('https://www.youtube.com/?gl='.constant("GJ_CODE").'&hl=zh-CN'); 
   $rules = array(
    't' => array('#feed .individual-feed .section-list li .item-section li .feed-item-container .feed-item-dismissable .shelf-title-table .shelf-title-row h2 .branded-page-module-title-text','text'),
    'html' => array('#feed .individual-feed .section-list li .item-section li .feed-item-container .feed-item-dismissable .compact-shelf .yt-viewport .yt-uix-shelfslider-list','html'),
        );

    $rules1 = array(
    'id' => array('li .yt-lockup ','data-context-item-id'),
    'title' => array('li .yt-lockup .yt-lockup-dismissable .yt-lockup-content .yt-lockup-title a','text'),
        );

    $data = QueryList::Query($dat,$rules)->data;
    
    $ldata=array();
    foreach ($data as $v) {
       $d = QueryList::Query($v['html'],$rules1)->data;
       $ldata[]=array(
           't'=> $v['t'],
           'dat' => $d
           );
    }
    array_shift($ldata);
    return $ldata;
}
//视频下载
function video_down($v,$name){
$yt = new YouTubeDownloader();
$links = $yt->getDownloadLinks("https://www.youtube.com/watch?v=$v");
echo '<table class="table table-hover"><thead><tr>
      <th>格式</th>
      <th>类型</th>
      <th>下载</th>
    </tr>
  </thead>';
foreach ($links as $value) {
    global $videotype;
echo ' <tbody>
    <tr>
      
      <td>'.$videotype[$value['format']][0].'</td>
      <td>'.$videotype[$value['format']][1].'</td>
      <td><a href="./downvideo.php?v='.$v.'&quality='.$value['format'].'&name='.$name.'&format='.$videotype[$value['format']][2].'" target="_blank" class="btn btn-outline-success btn-sm">下载</a></td>
    </tr></tbody>';
    } 
    echo '</table>';
}

//判断高清微缩图是否存在
function get_thumbnail_code($vid){
$thumblink='https://img.youtube.com/vi/'.$vid.'/maxresdefault.jpg';    
$oCurl = curl_init();
$header[] = "Content-type: application/x-www-form-urlencoded";
$user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";
curl_setopt($oCurl, CURLOPT_URL, $thumblink);
curl_setopt($oCurl, CURLOPT_HTTPHEADER,$header);
curl_setopt($oCurl, CURLOPT_HEADER, true);
curl_setopt($oCurl, CURLOPT_NOBODY, true);
curl_setopt($oCurl, CURLOPT_USERAGENT,$user_agent);
curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($oCurl, CURLOPT_POST, false);
$sContent = curl_exec($oCurl);
$headerSize = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
curl_close($oCurl);
if($headerSize == '404'){
  return 'https://img.youtube.com/vi/'.$vid.'/hqdefault.jpg';  
}else{
  return 'https://img.youtube.com/vi/'.$vid.'/maxresdefault.jpg';   
}
}


//解析历史记录
function Hislist($str,$apikey){
    $str=str_replace('@',',',$str);
    $apilink='https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id='.$str.'&key='.$apikey;
   return json_decode(get_data($apilink),true);   
}

//获取频道所属国家
$CountryID=array(
    'DZ' => '阿尔及利亚',
    'AR' => '阿根廷',
    'AE' => '阿拉伯联合酋长国',
    'OM' => '阿曼',
    'AZ' => '阿塞拜疆',
    'EG' => '埃及',
    'IE' => '爱尔兰',
    'EE' => '爱沙尼亚',
    'AT' => '奥地利',
    'AU' => '澳大利亚',
    'PK' => '巴基斯坦',
    'BH' => '巴林',
    'BR' => '巴西',
    'BY' => '白俄罗斯',
    'BG' => '保加利亚',
    'BE' => '比利时',
    'IS' => '冰岛',
    'PR' => '波多黎各',
    'PL' => '波兰',
    'BA' => '波斯尼亚和黑塞哥维那',
    'DK' => '丹麦',
    'DE' => '德国',
    'RU' => '俄罗斯',
    'FR' => '法国',
    'PH' => '菲律宾',
    'FI' => '芬兰',
    'CO' => '哥伦比亚',
    'GE' => '格鲁吉亚共和国',
    'KZ' => '哈萨克斯坦',
    'KR' => '韩国',
    'NL' => '荷兰',
    'ME' => '黑山共和国',
    'CA' => '加拿大',
    'CN' => '中国',
    'GH' => '加纳',
    'CZ' => '捷克共和国',
    'ZW' => '津巴布韦',
    'QA' => '卡塔尔',
    'KW' => '科威特',
    'HR' => '克罗地亚',
    'KE' => '肯尼亚',
    'LV' => '拉脱维亚',
    'LB' => '黎巴嫩',
    'LT' => '立陶宛',
    'LY' => '利比亚',
    'LU' => '卢森堡公国',
    'RO' => '罗马尼亚',
    'MY' => '马来西亚',
    'MK' => '马其顿',
    'US' => '美国',
    'PE' => '秘鲁',
    'MA' => '摩洛哥',
    'MX' => '墨西哥',
    'ZA' => '南非',
    'NP' => '尼泊尔',
    'NG' => '尼日利亚',
    'NO' => '挪威',
    'PT' => '葡萄牙',
    'JP' => '日本',
    'SE' => '瑞典',
    'CH' => '瑞士',
    'RS' => '塞尔维亚',
    'SN' => '塞内加尔',
    'SA' => '沙特阿拉伯',
    'LK' => '斯里兰卡',
    'SK' => '斯洛伐克',
    'SI' => '斯洛文尼亚',
    'TW' => '台湾',
    'TH' => '泰国',
    'TZ' => '坦桑尼亚',
    'TN' => '突尼斯',
    'TR' => '土耳其',
    'UG' => '乌干达',
    'UA' => '乌克兰',
    'ES' => '西班牙',
    'GR' => '希腊',
    'HK' => '香港',
    'SG' => '新加坡',
    'NZ' => '新西兰',
    'HU' => '匈牙利',
    'JM' => '牙买加',
    'YE' => '也门',
    'IQ' => '伊拉克',
    'IL' => '以色列',
    'IT' => '意大利',
    'IN' => '印度',
    'ID' => '印尼',
    'GB' => '英国',
    'JO' => '约旦',
    'VN' => '越南',
    'CL' => '智利',
    );
function get_country($c){
    global $CountryID;
    return  $CountryID[$c];
}

//url字符串加解密
function strencode($string,$key='09KxDsIIe|+]8Fo{YP<l+3!y#>a$;^PzFpsxS9&d;!l;~M>2?N7G}`@?UJ@{FDI') {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string,$i,1));
        if (@$j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        @$j++;
    @$hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
    return 'Urls://'.$hash;
}
function strdecode($string,$key='09KxDsIIe|+]8Fo{YP<l+3!y#>a$;^PzFpsxS9&d;!l;~M>2?N7G}`@?UJ@{FDI') {
    $string= ltrim($string, 'Urls://');
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
        if (@$j == $keyLen) { @$j = 0; }
        $ordKey = ord(substr($key,@$j,1));
        @$j++;
        @$hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}

//分享功能
function shareit($id,$title='免翻墙Youtube镜像'){
    $pic=ROOT_PART.'/thumbnail.php?vid='.$id;
    $url=ROOT_PART.'watch-'.$id.'.html';
    $title=str_replace('&','||',$title);
    $title=str_replace('"',' ',$title);
     $title=str_replace("'",' ',$title);
    $des='【免翻墙Youtube镜像】我正在通过这个网站看《'.$title.'》不用翻墙看全球视频，手机电脑都能看，快来试试吧！';
    return "<div id='share'>
  <a class='icoqz' href='https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=".$url."&desc=".$des."&title=".$titlel."
&pics=".$pic."' target='blank' title='分享到QQ空间'><i class='iconfont icon-qqkongjian icofontsize'></i></a>

  <a class='icotb' href='http://tieba.baidu.com/f/commit/share/openShareApi?title=".$title."&url=".$url."&to=tieba&type=text&relateUid=&pic=".$pic."&key=&sign=on&desc=&comment=".$title."' target='blank' title='分享到贴吧'><i class='iconfont icon-40 icofontsize'></i></a>

  <a class='icowb' href='http://service.weibo.com/share/share.php?url=".$url."&title=".$des."&pic=".$pic."&sudaref=".$title."' target='blank' title='分享到微博'><i class='iconfont icon-weibo icofontsize'></i></a>

  <a class='icobi' href='https://member.bilibili.com/v/#/text-edit' target='blank' title='分享到哔哩哔哩'><i class='iconfont icon-bilibili icofontsize'></i></a>

  <a class='icowx' href='http://api.addthis.com/oexchange/0.8/forward/wechat/offer?url=".ROOT_PART."watch.php?v=".$id."' target='blank' title='分享到微信' ><i class='iconfont icon-weixin icofontsize'></i></a>
</div>
 <div class='form-group'><div class='d-inline-block h6 pt-3 col-12'>
    分享代码：
 </div>
    <textarea style='resize:none;height: auto' class='form-control d-inline align-middle col-12 icoys icontext' id='inputs' type='text' rows='5' placeholder='Default input'><iframe height=498 width=510 src=&quot;".ROOT_PART."embed/?v=".$id."&quot; frameborder=0 &quot;allowfullscreen&quot;></iframe></textarea>
    
    <button type='submit' class='btn btn-primary align-middle col-12 mt-2' onclick='copytext1()'>复制</button></div>";
    
}
//
function html5_player($id){
    $yt = new YouTubeDownloader();
    $links = $yt->getDownloadLinks('https://www.youtube.com/watch?v='.$id);
    if(count($links)!=1){
        echo'<video id="h5player"  class="video-js vjs-fluid mh-100 mw-100" loop="loop" width="100%" preload="auto"  webkit-playsinline="true" playsinline="true" x-webkit-airplay="true" controls="controls" controls preload="auto" width="100%" poster="./thumbnail.php?type=maxresdefault&vid='.$id.'" data-setup=\'\'>';
        
        //获取视频分辨率
        if(array_key_exists('22',$links)){
        echo '<source src="./vs.php?vv='.$id.'&quality=720" type=\'video/mp4\' res="720" label=\'720P\'/>';   
            };
        echo '<source src="./vs.php?vv='.$id.'&quality=360" type=\'video/mp4\' res="360" label=\'360P\'/>';
        
        
    //提取字幕
     $slink='https://www.youtube.com/api/timedtext?type=list&v='.$id;
     $vdata=get_data($slink);
     @$xml = simplexml_load_string($vdata);
     $array1=json_decode(json_encode($xml), true);
     $arr=array();
     //分离出几种常用字幕
     if(array_key_exists('track',$array1) && array_key_exists('0',$array1['track'])){
         if (array_key_exists('track', $array1) && array_key_exists('0', $array1['track'
    									   ])) {
    	foreach ($array1['track'] as $val) {if ($val['@attributes']['lang_code'] == 'en' || $val['@attributes']['lang_code'] == 'zh' || $val['@attributes']['lang_code'] =='zh-CN' || $val['@attributes']['lang_code'] =='zh-TW' || $val['@attributes']['lang_code'] =='zh-HK') {
    			$arr[$val['@attributes']['lang_code']] = "
    <track kind='captions' src='./tracks.php?vtt={$id}&lang=" . $val['@attributes']
    ['lang_code'] . "' srclang='" . $val['@attributes']['lang_code'] . "' label='" .
    				   $val['@attributes']['lang_original'] . "'/>";
    		}
    	}
    	foreach ($arr as $k => $v) {
    	    switch ($k) {
    		    case 'zh-CN':
    		        $arr[$k] = substr_replace($v, ' default ', -2,0);
    				break;
    			case 'zh':
    		        $arr[$k] = substr_replace($v, ' default ', -2,0);
    				break;
    			case 'zh-HK':
    		        $arr[$k] = substr_replace($v, ' default ', -2,0);
    				break;
    			case 'zh-TW':
    				$arr[$k] = substr_replace($v, ' default ', -2,0);
    				break;
    			case 'en':
    				$arr[$k] = substr_replace($v, ' default ', -2,0);
    				break;
    		}
    		break;
    	}
    	foreach($arr as $vl ){
          echo $vl.PHP_EOL;
      }
    }
     }elseif(array_key_exists('track',$array1)){
     echo "<track kind='captions' src='./tracks.php?vtt={$id}&lang=".$array1['track']['@attributes']['lang_code']."' srclang='".$array1['code']."' label='".$array1['track']['@attributes']['lang_original']."' default />";   
     }

    echo '</video>';
    }else{
        echo '<img src="./inc/2.svg" class="w-100" onerror="this.onerror=null; this.src="./inc/2.gif"">';       
        }   
}
//获取安装目录
function Root_part(){
$http=isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$part=rtrim($_SERVER['SCRIPT_NAME'],basename($_SERVER['SCRIPT_NAME']));
$domain=$_SERVER['SERVER_NAME'];
 return "$http"."$domain"."$part";
}
?>
