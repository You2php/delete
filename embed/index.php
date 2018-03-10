<?php 
if(!is_array($_GET)&&count($_GET)>0){ header("Location: ../error.php"); exit();} include("../lib.php"); 
?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <base href="<?php echo trim(Root_part()," embed/ ") ?>/" />
        <link href="//cdn.bootcss.com/video.js/5.20.4/alt/video-js-cdn.min.css" rel="stylesheet" />
        <script src="//libs.cdnjs.net/video.js/5.20.4/video.min.js"></script>
        <script type="text/javascript" src="./inc/4.js"></script>
        <link rel="stylesheet" href="./inc/theme.css" type="text/css">
    </head>
    
    <body>
        <div style="max-width:100%;height:auto">
            <?php html5_player($_GET[ 'v']); ?>
            <script>
            videojs('h5player').videoJsResolutionSwitcher();
            </script>
        </div>
    </body>

</html>