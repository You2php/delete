<?php
if(!is_array($_GET)&&count($_GET)<=0){
       exit();
    }
include("./lib.php");
$channel=get_channel_info($_GET['channelid'],APIKEY);
$headtitle=$channel['items'][0]['snippet']['title'].'-'.SITE_NAME;
include("./header.php"); 
if(isset($_GET['pageToken'])){$ptk=$_GET["pageToken"];} else {$ptk='';}
?>
<div class="bg1" style="background: url(./thumbnail.php?type=banner&vid=<?php echo $channel['items'][0]['id'] ?>);background-repeat: no-repeat;background-position: center center;background-size: cover;" >
          <div class="touxiang">  
          <div id="bg2">
              <div id="bg3" style="border-radius: 100%;background: url(./thumbnail.php?type=photo&vid=<?php echo $channel['items'][0]['id'] ?>) center no-repeat;background-size: cover;"> </div></div>
         <div class="container pt-2">
                 
                  <div id="bg4" class="txt2"><?php echo $channel['items'][0]['snippet']['title'] ?></div>
          
           <p class="kkkkkk pb-1 text-center" style="color: white;">
               <?php
               if(!empty($channel['items'][0]['snippet']['description'])){echo $channel['items'][0]['snippet']['description'];}else{echo 'Dieser Typ ist faul, nichts mehr übrig...';}?>
               </p>
          <script>
$(document).ready(function(){
	$('.kkkkkk').collapser({
	 mode: 'lines',
        truncate: 1,
        showText: '<i class="fa d-inline fa-2x fa-angle-down shangico"></i>',
        hideText: '<i class="fa d-inline fa-2x fa-angle-up shangico"></i>',
   	});	
});
</script>
          
             </div> 
      </div>
      <div class="bg10"></div>
          </div>
 
  <div class="container pt-2">
      <div class="py-2">
    <div class="row">
        <div class="container">
            
            
       
     <div class="row equipo-item">
 <div class="col-md-4 col-sm-12 col-xs-12 col-lg-4 equipodiv order-md-1 order-lg-1 order-xs-2 order-sm-2">
  <div class="related">
      <p class="font-weight-bold h6 pb-1"> Kanalinformationen</p>
       <span class="d-block py-3 fa fa-calendar"> Registriert seit <?php echo date("Y-m-d", strtotime($channel['items'][0]['snippet']['publishedAt']));?></span>  
       <span class="d-block py-3 fa fa-play"> <?php echo $channel['items'][0]['statistics']['viewCount']?> Zuschauer</span> 
       <span class="d-block py-3 fa fa-user-circle-o"> <?php echo $channel['items'][0]['statistics']['subscriberCount']?> Abonnenten</span>
       <span class="d-block py-3 fa fa-globe"> Aus <?php if (array_key_exists('country',$channel['items'][0]['snippet'])) {echo get_country($channel['items'][0]['snippet']['country']);} else {echo '火星';}?></span> 

  </div>
  <div class="related text-nowrap">
      <p class="font-weight-bold h6 pb-1">Verwandter Kanal</p>
       <?php
       $xg=get_related_channel($channel['items'][0]['id']);
       foreach ($xg as $v) {
           echo '<span class="d-block">
                    
                    <a href="./channel.php?channelid='.$v['id'].'" target="_blank"> <img src="./thumbnail.php?type=photo&amp;vid='.$v['id'].'" class="txlistimg"></a>
                    
                    <a href="./channel.php?channelid='.$v['id'].'" target="_blank"  title="'.$v['name'].'"class="text-dark txlist"> 
                    '.$v['name'].'</a>
                    
                    </span>';
       }
       ?>

  </div>
 </div>
 <div class="col-md-8 col-sm-12 col-xs-12 col-lg-8 related order-md-2 order-lg-2 order-xs-1 order-sm-1">
    <div class="font-weight-bold h6 pb-1">Alle Videos (<?php echo $channel['items'][0]['statistics']['videoCount'] ?>个）</div> 
    <div id="videocontent"></div>
    <script>$("#videocontent").load('<?php echo './ajax/ajax.php?channelid='.$_GET['channelid'].'&type=channels&ptk='.$_GET['pageToken']?>');</script>
</div>
	</div>

	
	
 </div>
 </div>
    
    </div>
    </div>
 
 
 </div>
  <?php
include("./footer.php"); 
?>