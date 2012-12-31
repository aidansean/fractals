<?php

include('functions.php') ;
$image = imagecreatetruecolor($width,$height) ;

// Save some lookup/division time
$wx = ($vars['xMax']-$vars['xMin'])/$width  ;
$wy = ($vars['yMax']-$vars['yMin'])/$height ;

for($row=0 ; $row<$height ; $row+=$size){
  for($col=0 ; $col<$width ; $col+=$size){
    $i = 0 ;
    $x0 = $vars['xMin'] + $col*$wx ;
    $y0 = $vars['yMax'] - $row*$wy ;
    $i = getI($x0,$y0) ;
    $color = getColor($image,$i,$vars['palette']) ;
    imagefilledrectangle($image,$col,$row,$col+$size,$row+$size,$color) ;
  }
}

header('Content-type: image/png') ;    
imagepng($image) ;
imagedestroy($image) ;

?>

