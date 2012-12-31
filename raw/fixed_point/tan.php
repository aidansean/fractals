<?php

include('functions.php') ;

$p = pi() ;
$xMin = -$p ; $xMax = $p ;
$yMin = -$p ; $yMax = $p ;
$palette = 9 ;

// Width and height of the canvas
$width  = 2000 ;
$height = 2000 ;
$image = imagecreate($width,$height) ;

$size =  1 ;
$iMax = 1023 ;

$tolerance  = 0.01 ;
$tolerance2 = $tolerance*$tolerance ;

// Now loop over all the pixels one at a time
$rX = ($xMax-$xMin)/$width  ;
$rY = ($yMax-$yMin)/$height ;
for($row=0 ; $row<$height ; $row+=$size){
  for($col=0 ; $col<$width ; $col+=$size){
    $x = $xMin + $col*$rX ;
    $y = $yMax - $row*$rY ;
    $i = get_i(array($x,$y), $iMax) ;
    $color = get_color($image,$i[0],$iMax,$palette) ;
    imagefilledrectangle($image,$col,$row,$col+$size,$row+$size,$color) ;
  }
}

// Create image
header("Content-type: image/png") ;    
imagepng($image) ;
imagedestroy($image) ;

// Find out how many iterations for this pixel
function get_i($z, $iMax){
  global $tolerance2 ;
  $i = 0 ;
  $escape = false ;
  while($escape==false AND $i<$iMax) {
    $z1 = complex_tan($z) ;
    if(distance2($z1,$z)<$tolerance2) $escape = true ;
    $z = $z1 ;
    $i++ ;
  }
  return array($i, $z) ;
}

function complex_divide($z1,$z2){
  $r2 = $z2[0]*$z2[0] + $z2[1]*$z2[1] ;
  if($r2==0) return array(0,0) ;
  return array( ($z1[0]*$z2[0]+$z1[1]*$z2[1])/$r2 , ($z1[1]*$z2[0]-$z1[0]*$z2[1])/$r2 ) ;
}
function distance2($z1, $z2){ return ($z1[0]-$z2[0])*($z1[0]-$z2[0])+($z1[1]-$z2[1])*($z1[1]-$z2[1]) ; }
function complex_sin($z){ return array( sin($z[0])*cosh($z[1]) ,  cos($z[0])*sinh($z[1]) ) ; }
function complex_cos($z){ return array( cos($z[0])*cosh($z[1]) , -sin($z[0])*sinh($z[1]) ) ; }
function complex_tan($z){ return complex_divide(complex_sin($z),complex_cos($z)) ; }
function complex_log($z){ return array( atan2($z[1],$z[0]) , 0.5*log($z[0]*$z[0]+$z[1]*$z[1]) ) ; }

?>

