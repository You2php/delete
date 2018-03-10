<?php
    include('./lib.php');
    if(!is_array($_GET)&&count($_GET)>0){
        exit();
    }
    $videodata=get_video_info($_GET['v'],APIKEY);
    $headtitle=$videodata['items']['0']['snippet']['title'].' - '.SITE_NAME;
    include("./header.php"); 
    if($videodata['pageInfo']['totalResults'] == '0' && $videodata['pageInfo']['resultsPerPage']== '0'){
      header("Location: ./error.php");
      exit();
    }
    //记录历史浏览观看记录
    $tt=time()+1814400;
    if(!isset($_COOKIE['history'])){
     setcookie("history",$videodata['items']['0']['id'], $tt);   
    }else{
        
    $history=$_COOKIE['history'];
    $histmp=explode('@',$history);
    
    //重复的浏览历史只记录最新一次
if (in_array($videodata['items']['0']['id'] ,$histmp)){
        $akey=array_search($videodata['items']['0']['id'],$histmp);
         unset($histmp[$akey]);
         array_unshift($histmp,$videodata['items']['0']['id']); 
    }else{
     array_unshift($histmp,$videodata['items']['0']['id']);    
    }
    
    //最大观看记录条数，超出的删除
    if(count($histmp)==30){
    array_pop($histmp);
    }
    //防止cookies字节溢出
    if(count($histmp)==40){
    $histmp=array_slice($histmp,20);
    }
    
    $histmp1=implode('@',$histmp);
    setcookie('history',$histmp1, $tt);
    }
?>
<div class="container container-top">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12 col-lg-8 leffix">
             <div id="videoplayer" class="w-100">
                 <?php html5_player($videodata['items']['0']['id']);?>
             </div>
             
            
            <div class="vinfo fsize2 clearfix">
                <h1 class="fsize1 pt-4 pb-2 d-block text-dark"><?php echo $videodata['items']['0']['snippet']['title'] ?></h1>

                <span class="pull-left"><i class="fa fa-clock-o"></i> <?php echo str2time($videodata['items']['0']['snippet']['publishedAt']) ?></span>
                <span class="pull-right"><i class="fa fa-eye"></i> <?php echo convertviewCount($videodata['items']['0']['statistics']['viewCount']) ?></span> 
                
                <div class="pt-3" style="clear: both;">
                    <p id="des"><?php 
        				if(!empty($videodata['items']['0']['snippet']['description'])){ 
        				    preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $videodata['items']['0']['snippet']['description'], $match);
        				    $description=$videodata['items']['0']['snippet']['description'];
        				    foreach ($match[0] as $v) {
        				    $k='<a href="./link.php?u='.strencode($v,EN2DEKEY).'"  target="_blank">'. $v.'</a>';
        				    $description=str_replace($v,$k,$description);  
        				    }
        				     echo $description;
        				    } else { 
        				        echo 'Diese Person ist faul und schreibt keine Beschreibung.';
        				};
			         ?>   
                    </p>
                <div id="gradient"></div>
				<div id="read-more"></div>
				
                </div>
                <span class="pull-left ">Kategorie<a href="./content.php?cont=category&sortid=<?php echo $videodata['items']['0']['snippet']['categoryId']?>" class="pl-2 d-inline"  target="_blank"><?php echo categorieslist($videodata['items']['0']['snippet']['categoryId'])?></a>
                </span>
                <span class="pull-right">
                    <span id="fxs" data-toggle="popover" title="Teilen" data-html="true" data-placement="left" data-content="<?php echo shareit($videodata['items']['0']['id'],$videodata['items']['0']['snippet']['title']);?>">
                    <i class="fa fa-share-square-o pr-1"></i>Teilen
                </span>
                <span class="pr-1">
                    <a href="./content.php?cont=video&v=<?php echo $videodata['items']['0']['id'] ?>" target="_blank" class="fsize2"><i class="fa fa-arrow-down pr-1"></i>Herunterladen</a>
                </span>
                </span>
             </div>
                
                <div id="accdiv" class="px-3 py-2 mt-2 fsize2 clearfix ">
                    <span class="d-inline-block pull-left ">
                    
                    <a href="./channel.php?channelid=<?php echo $videodata['items']['0']['snippet']['channelId'] ?>"> <img src="./thumbnail.php?type=photo&vid=<?php echo $videodata['items']['0']['snippet']['channelId']?>" id="touxiang"></a>
                    
                    <a href="./channel.php?channelid=<?php echo $videodata['items']['0']['snippet']['channelId'] ?>" title="<?php echo $videodata['items']['0']['snippet']['channelTitle'] ?>" id="touaianga" class="text-dark"> 
                    <?php echo $videodata['items']['0']['snippet']['channelTitle'] ?></a>
                    
                    </span>
                    
                    <a href="./channel.php?channelid=<?php echo $videodata['items']['0']['snippet']['channelId'] ?>" class="btn btn-sm btn-c pull-right fsize2 my-1"><p class="text-primary m-0 px-2">Kanal</p></a>
                   
                </div>
        <div id="tags" class="w-100 pb-2 vinfo">
            
            <?php
            foreach ($videodata['items'][0]['snippet']['tags'] as $v) {
               echo '<span><a href="./search.php?q='.$v.'" target="_blank">'.$v.'</a></span>';
            }?>
          
        </div></div>
  

        
         <div class="col-md-4 col-sm-12 col-xs-12 col-lg-4 related">
            <div class="font-weight-bold h6 pb-1">Relevanter Inhalt</div> 
            <div id="videocontent"></div>
            <script>$("#videocontent").load('<?php echo './ajax/ajax.php?v='.$_GET['v'].'&type=related'?>');</script>
         </div>
    </div>
</div>
<script>
$(function(){
 var slideHeight = 60; // px
 var defHeight = $('#des').height();
 if(defHeight >= slideHeight){
  $('#des').css('height' , slideHeight + 'px');
  $('#read-more').append('<i class="fa d-inline fa-lg fa-caret-down"></i>');
  $('#read-more').click(function(){
   var curHeight = $('#des').height();
   if(curHeight == slideHeight){
    $('#des').animate({
     height: defHeight
    }, "normal");
    $('#read-more').html('<i class="fa d-inline fa-caret-up fa-lg">');
    $('#gradient').fadeOut();
   }else{
    $('#des').animate({
     height: slideHeight 
    }, "normal");
    $('#read-more').html('<i class="fa d-inline fa-lg fa-caret-down"></i>');
    $('#gradient').fadeIn();
   }
   return false;
  });  
 }
});
    videojs('h5player').videoJsResolutionSwitcher();
    $(function () {$('[data-toggle="popover"]').popover()});
</script>
<?php
include("./footer.php"); 
?>