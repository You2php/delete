<?php
if(!is_array($_GET)&&count($_GET)<=0){
       exit();
    }
header('content-type:image/jpg;');
include('./lib.php');

$vid = $_GET['vid'];
switch ($_GET['type'])
{
    case "mqdefault":
        $vidimg = 'https://i.ytimg.com/vi/' . $vid . '/mqdefault.jpg';
        break;
    case "0":
        $vidimg = 'http://img.youtube.com/vi/' . $vid . '/0.jpg';
        break;
    case "photo":
        $photo  = get_channel_info($vid, APIKEY);
        $vidimg = $photo['items'][0]['snippet']['thumbnails']['default']['url'];
        break;
    case "maxresdefault":
        $vidimg = get_thumbnail_code($vid);
        break;
    case "banner":
        $vidimg = get_banner($vid, APIKEY);
        break;
    default:
        $vidimg = 'https://i.ytimg.com/vi/' . $vid . '/default.jpg';
}
$img = get_data($vidimg);
echo $img;
?> 