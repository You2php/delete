<?php 
include "./lib.php";
$cont   = $_GET['cont'];
$ptk    = $_GET['pageToken'];
$sortid = $_GET['sortid'];
$order  = isset($_GET['order']) ? $_GET['order'] : 'relevance';
switch ($cont) {
case 'history':
    $headtitle='Verlauf ';
	break;
case 'category':
    $headtitle=categorieslist($sortid).'-'.SITE_NAME;
	break;
case 'trending':
    $headtitle='Trends '.'-'.SITE_NAME;
	break;
case 'DMCA':
    $headtitle='DMCA '.'-'.SITE_NAME;
	break;
case 'video':
    $headtitle='Downloader '.'-'.SITE_NAME;
	break;
case 'api':
    $headtitle='API '.'-'.SITE_NAME;
	break;

}

include "./header.php";

if($cont=="trending"){
    echo'<div class="container-fluid d-lg-none d-md-none" style="background:#e62117">
  <div class="container">
       <div class="row text-center" >
        <div class="col-4"><a class="navbar-brand topbara" href="./"><i class="fa d-inline fa-lg fa-home txt-topbar"></i></a></div>
        <div class="col-4"><a class="navbar-brand topbara" href="./content.php?cont=trending"><i class="fa d-inline fa-lg fa-fire text-white"></i></a></div>
        <div class="col-4"><a class="navbar-brand topbara" href="./content.php?cont=history"><i class="fa d-inline fa-lg fa-history txt-topbar"></i></a></div>
  </div> 
</div>
</div>';
}elseif ($cont=="history") {
 echo '<div class="container-fluid d-lg-none  d-md-none" style="background:#e62117">
  <div class="container">
       <div class="row text-center" >
        <div class="col-4"><a class="navbar-brand topbara" href="./"><i class="fa d-inline fa-lg fa-home txt-topbar"></i></a></div>
        <div class="col-4"><a class="navbar-brand topbara" href="./content.php?cont=trending"><i class="fa d-inline fa-lg fa-fire txt-topbar"></i></a></div>
        <div class="col-4"><a class="navbar-brand topbara" href="./content.php?cont=history"><i class="fa d-inline fa-lg fa-history text-white"></i></a></div>
  </div> 
</div>
</div>';
}

?>

  <div class="container py-2">
     <div class="row">
         <div class="col-md-3 d-none d-sm-none d-md-block">
          <div id="menu"></div>
    		<script>$("#menu").load('<?php echo './ajax/ajax.php?type=menu' ?>');</script>
         </div>
         <div class="col-md-9 relatedlist">
            <?php 
            switch ($cont) {
                    case 'history':
		        echo '<div id="history"></div>
                     <script>
                     $("#history").load(\'./ajax/ajax.php?type=history\');
                     </script>';
		             break;
		             
		            case 'DMCA':
		        echo '<div id="DMCA"></div>
                     <script>
                     $("#DMCA").load(\'./ajax/ajax.php?type=DMCA\');
                     </script>';
		             break;
		             
		             case 'api':
		        echo '<div id="api"></div>
                     <script>
                     $("#api").load(\'./ajax/ajax.php?type=api\');
                     </script>';
		             break;
		        case 'video':
		        echo '<div id="videos"></div>
                     <script>';
                    $g=isset($_GET['v'])?"&v={$_GET['v']}":'';
                     echo '$("#videos").load(\'./ajax/ajax.php?type=videos'.$g.'\');
                    </script>';
		             break;
		             
		           case 'trending':
		        echo '<div id="videocont"></div>
                        <script>
                        $("#videocont").load(\'./ajax/ajax.php?type=trendinglist&ptk=' . $ptk . '\');
                        </script>';
		            break;
		            
            		case 'category':
            		switch ($order) {
            		    case 'date':
            				$date1 = 'selected';
            				break;
            				case 'viewCount';
            				$viewCount = 'selected';
            				break;
            				default:
            				$relevance = 'selected';
            		   }
            		  
            		if(isset($_GET['sortid'])){echo '<div class="font-weight-bold h6 pb-1">'.categorieslist($sortid).'</div> ';}
            		echo '<div class="row"> <div class="col-md-12 selectalign pb-3"><select class="custom-select" id="paixu">';
            		echo '<option '. $relevance .' data-url="./ajax/ajax.php?type=category&sortid='.$sortid.'&ptk='.$ptk.'">Beliebte Videos</option>';
            		echo '<option ' . $date1 .' data-url="./ajax/ajax.php?type=category&sortid='.$sortid.'&order=date&ptk='.$ptk.'">Neueste Version</option>';
            		echo '<option ' . $viewCount .' data-url="./ajax/ajax.php?type=category&sortid='.$sortid.'&order=viewCount&ptk='.$ptk.'">Die meisten Klicks</option>';
            		echo '</select></div></div>';
            		echo '<div id="videocont"></div><script>$("#videocont").load(\'./ajax/ajax.php?type=category&sortid='. $sortid .'&order='.$order.'&ptk='.$ptk.'\');$(\'#paixu\').on(\'change\', function() {loadPage($(this).find(\':selected\').data(\'url\'));});function loadPage(url) {$("#videocont").load(url);}</script>';
            		        break;
		
		}?>
		
		</div>
		
     </div>
  </div>

<?php include "./footer.php";?>