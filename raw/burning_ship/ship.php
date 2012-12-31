<?php

$_GET['small'] = -1 ;
$_GET['xMin']  = -2 ;
$_GET['xMax']  =  2 ;
$_GET['yMin']  = -2 ;
$_GET['yMax']  =  2 ;
$_GET['iMax']  = 1023 ;

include('functions.php') ;
$vars['palette'] = 9 ;
$image = imagecreatetruecolor($width,$height) ;
$wx = ($vars['xMax']-$vars['xMin'])/$width  ;
$wy = ($vars['yMax']-$vars['yMin'])/$height ;
for($row=$height-1 ; $row>=0 ; $row-=$size){
  for($col=0 ; $col<$width ; $col+=$size){
    $i = 0 ;
    $x0 = $vars['xMin'] + $col*$wx ;
    $y0 = $vars['yMin'] + $row*$wy ;
    $i = getIShip($x0,$y0) ;
    $color = getColor($image,$i,$vars['palette']) ;
    imagefilledrectangle($image,$col,$row,$col+$size,$row+$size,$color) ;
  }
}

header("Content-type: image/png") ;    
imagepng($image) ;
imagedestroy($image) ;

?>

