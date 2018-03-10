<?php
include('./lib.php');
if(!is_array($_GET)&&count($_GET)>0){
    exit();
}
$str=strdecode($_GET['u'],EN2DEKEY);
 if(stripos($str,'youtu.be')!==false || stripos($str,'watch?v=')!==false ){
     preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $str, $matches);
     $str='./watch.php?v='.$matches[1];
     header("Location:$str");
     }else{
      header("Location:$str");   
     }
?>

