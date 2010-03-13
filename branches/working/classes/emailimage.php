<?php
header ("Content-type: image/png");
require_once('../config.php');
$image = imagecreate(300,16);

$blanc = imagecolorallocate($image, 255, 255, 255);
$noir = imagecolorallocate($image, 0, 0, 0);

imagestring($image, 4, 0, -1, $_GET['text'].'@'.$emailDomaine, $noir);
imagecolortransparent($image, $blanc);

imagepng($image);
?>
