<?php
if(!file_exists('./config.php')){
      header('Location: ./install.php');
      exit();   
}
include("./lib.php");
$headtitle='首页'.'-'.SITE_NAME;
include("./header.php");
if(isset($_GET['v'])){
    if(stripos($_GET['v'],'youtu.be')!==false || stripos($_GET['v'],'watch?v=')!==false ){
     preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $_GET['v'], $matches);
    $str='./watch.php?v='.$matches[1];
     header("Location:$str");
     exit();
     }else{
     $str='./search.php?q='.$_GET['v'];
     header("Location: $str");
     exit();
}
}
?>
<div class="container-fluid d-lg-none  d-md-none" style="background:#e62117">
    
  <div class="container p-1">
       <div class="row text-center p-1" >
        <div class="col-4"><a class="topbara" href="./"><i class="fa d-inline hico fa-home text-white"></i></a></div>
        <div class="col-4"><a class="topbara" href="./content.php?cont=trending"><i class="fa d-inline hico fa-fire txt-topbar"></i></a></div>
        <div class="col-4"><a class="topbara" href="./content.php?cont=history"><i class="fa d-inline hico fa-history txt-topbar"></i></a></div>
  </div> 
  
  
</div>
</div>


<div class="container d-lg-none d-md-none py-2" style="padding-right: 5px;padding-left: 5px;">
<div class="row ml-0 mr-0">


<div class="topmenu w-100 px-2">  
    <div class="swiper-container">  
        <div class="swiper-wrapper">
            <?php
            echo '<span class="swiper-slide"><a href="./" class="text-primary">首页</a></span>';
            foreach (categorieslist('all') as $k => $val) {
                echo '<span class="swiper-slide"><a href="./content.php?cont=category&sortid='.$k.'" class="text-dark">'.$val.'</a></span>';  
            }
            ?>
            
  
            
        </div>  
    </div>  
    
</div>  

  
</div></div>

<div class="container d-lg-none d-md-none p-0">
    
  <div id="lb" class="carousel slide" data-ride="carousel" width="100%">
 <?php
  $feedlist=random_recommend();
  $feed=array();
    foreach ($feedlist as $v) {
      $feed[]=$v['dat'][0];  
    }
 echo ' <ul class="carousel-indicators">';
 for ($i = 1; $i < count($feed); $i++) {
      if($i == 1){
      echo '<li data-target="#lb" data-slide-to="'.$i.'" class="active"></li>';   
      }else{
       echo '<li data-target="#lb" data-slide-to="'.$i.'"></li>'; 
      }
 }
 echo '</ul>
 <div class="carousel-inner">';
 foreach ($feed as $key => $val) {
 if($key==0){
       echo '
       <div class="carousel-item active">
       <a href="./watch.php?v='.$val['id'].'">
       <img src="./thumbnail.php?type=mqdefault&vid='.$val['id'].'" class="img-responsive">
       </a>
      <div class="carousel-caption">
        <p class="my-0 text-white">'.$val['title'].'</p>
      </div>
    </div>'; 
    }else{
       echo '<div class="carousel-item">
        <a href="./watch.php?v='.$val['id'].'">
      <img src="./thumbnail.php?type=mqdefault&vid='.$val['id'].'" class="img-responsive">
      </a>
      <div class="carousel-caption text-truncate">
         <pclass="my-0 text-white">'.$val['title'].'</p>
      </div>
    </div>'; 
    }
 }
    echo '</div>';
 ?>

 

 
</div>
    
</div>

  <div class="container py-2">
      
    <div class="row">
      <div class="col-md-3 d-none d-sm-none d-md-block">
          <div id="menu"></div>
          <script>$("#menu").load('<?php echo './ajax/ajax.php?type=menu'?>');</script>
    </div>
      <div class="col-md-9 ">
         <div class="col-md-12 pb-3 d-none d-sm-none d-md-block" style="background: url(https://wx2.sinaimg.cn/large/b0738b0agy1fm4j4ba83wj20t60jh0uf.jpg) no-repeat;background-size: cover;background-position: center -80px;text-align: center;
    "> 
   <h3 class="pt-5 pb-2 text-white">你好 世界！</h3>
    <form>
  <div class="form-group" >
      <input type="text" name="v" style="width: 50%;height: 50px;border: none;box-sizing: border-box;padding: 14px 18px;" placeholder="请输入视频ID或Youtube链接"  autocomplete="off" /><button type="submit"  style="width: 18%;border: none;height: 50px;background-color: #e62117;color: #fff;font-size: 18px;display: inline-block;" ><i class="fa fa-youtube-play fa-lg pr-1"></i>播放</button>
  </div>
    </form>
    <p class="text-white m-0">社会主义核心价值观:</p>
    <p class="text-white m-0">富强、民主、文明、和谐</p>
    <p class="text-white m-0">自由、平等、公正、法治</p>
    <p class="text-white m-0">爱国、敬业、诚信、友善</p>
    </div>
    
    <div class="row pt-2 pb-2">
    <div class="col-8 sm-p">
      <span class="txt2 ricon h5">时下流行</span>
    </div>
    <div class="col-4 text-right sm-p">
      <a href="./content.php?cont=trending" title="更多" target="_blank" class="icontext h6 pl-1 ">更多<i class="fa d-inline fa-lg fa-angle-double-right"></i></a>
    </div>
  </div>
         
          
        
            <div id="videocontent" class="videocontentrow"></div>
            <div id="recommend" class="videocontentrow"></div>
    <script>
    $("#videocontent").load('./ajax/ajax.php?type=trending');
    $("#recommend").load('./ajax/ajax.php?type=recommend');
    </script>
    
        </div>
    </div>
 </div>
<?php
include("./footer.php"); 
?>
