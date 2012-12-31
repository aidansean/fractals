<?php

include('functions.php') ;
$size = 2 ;
$nGrades = 256 ;
$width = $nGrades*$size ;
$height = 20 ;
$image = imagecreate($width,$height) ;
for($i=1 ; $i<$nGrades ; $i++){
  $color = getColor($image,$i,$_GET['palette']) ;
  imagefilledrectangle($image,$i*$size,0,($i+1)*$size,$height,$color) ;
}

header('Content-type: image/png') ;
imagepng($image) ;

?>

