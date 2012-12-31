<?php
// I can't remember if this works or not!
// It probably doesn't

include('functions.php') ;
$width  = 375 ;
$height = 375 ;
$size   =   1 ;

$currentIMin = $iMax ;
$currentIMax = $iMin ;
$findIMinIMax = true ;

for($row=0 ; $row<$height ; $row+=$size){
  for($col=0 ; $col<$width ; $col+=$size){
    $i = 0 ;
    $x0 = $xMin + ($col/$width )*($xMax-$xMin) ;
    $y0 = $yMax - ($row/$height)*($yMax-$yMin) ;
    $x1 = 0 ;
    $y1 = 0 ;
    $x2 = 0 ;
    $y2 = 0 ;
    getI($x0,$y0) ;
  }
}
$iMin = $currentIMin ;
$iMax = $currentIMax ;
echo '<a href="index.php?iMin=' . $iMin . '&iMax=' . $iMax . '&xMin=' . $xMin . '&xMax=' . $xMax . '&yMin=' . $yMin . '&yMax=' . $yMax .'&n=' . $n . '">Update colors</a><br />' . PHP_EOL  ;

?>

