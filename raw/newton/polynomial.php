<?php

$roots = array() ;
$roots[0] = array(  1,  0) ;
$roots[1] = array(  0,  2) ;
$roots[2] = array( -3,  0) ;
//$roots[3] = array(  0, -5) ;
//$roots[4] = array(  8,  0) ;
//$roots[5] = array(  0, 13) ;
//$roots[6] = array(-21,  0) ;
//$roots[7] = array(  0,-34) ;

$a = array(array(1,0)) ;
for($i=0 ; $i<count($roots) ; $i++){ $a = combine_terms($a,complex_multiply(array(-1,0),$roots[$i])) ; }
for($i=0 ; $i<count($a) ; $i++){
  echo '(' , $a[$i][0] , ',' , $a[$i][1] , ')z^' , $i ;
  if($i<count($a)) echo ' + ' ;
}
echo PHP_EOL ;
$d = complex_differential($a) ;
for($i=0 ; $i<count($d) ; $i++){
  echo '(' , $d[$i][0] , ',' , $d[$i][1] , ')z^' , $i ;
  if($i<count($d)) echo ' + ' ;
}
echo PHP_EOL ;

function combine_terms($a, $b){
  // $b must be a linear function ie z-(x+iy)
  // $a is a polynomial in terms of (x+iy)z^n
  $a_next = array() ;
  for($i=0 ; $i<=count($a) ; $i++){ $a_next[$i] = array(0,0) ; }
  for($i=0 ; $i<=count($a) ; $i++){
    $a_next[$i] = complex_add($a_next[$i], complex_multiply($a[$i], $b)) ;
    if($i!=0) $a_next[$i] = complex_add($a_next[$i], $a[$i-1]) ;
  }
  echo count($a_next) , PHP_EOL ;
  return $a_next ;
}
function complex_differential($a){
  $diff = array() ;
  for($i=1 ; $i<count($a) ; $i++){
    $diff[$i-1] = complex_multiply(array($i,0), $a[$i]) ;
  }
  return $diff ;
}
function complex_add     ($a,$b){ return array($a[0]+$b[0],$a[1]+$b[1]) ; }
function complex_multiply($a,$b){ return array($a[0]*$b[0]-$a[1]*$b[1],$a[0]*$b[1]+$a[1]*$b[0]) ; }

?>

