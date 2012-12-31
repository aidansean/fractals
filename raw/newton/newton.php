<?php

include('functions.php') ;

// Short version:
// Pick some roots
// Pick some palettes based on the roots
// Use like this:
// php newton.php > output.png

// If output.png is not readable then you need to check for error messages with:
// less output.png
// The plain text will screw with the header information, so error messages appear early on in the file.
// If these are just warnings then PHP will still dump the binary to the file, so the later parts of the file will look like garbage!
// Such files are of no use to anyone except debuggers

// This function can be used to pick the palette
// (see below for how palettes work)
// (see palettes.html for a list of palettes)
function palette_picker($root){ return 12+($root%3) ; }

// Range of the real and imaginary axes
// x axis for real, y axis for imaginary (duh)
$xMin = -2 ;
$xMax =  2 ;
$yMin = -2 ;
$yMax =  2 ;

// Width and height of the canvas
// 5000px x 5000px is about the maximum you can do on a mac before running out of allocatable memory
$width  = 500 ;
$height = 500 ;

// Granularity of the fractal
// You can can make the image from 2x2 squares by setting size=2, for example
// You can use this to speed up "rough guesses" to see what might look good
// A factor of 3 gives you roughly an order of magnitude in difference of CPU time
$size   = 1 ;

// Some points will never converge, so we need to set the maximum number of iterations
// Anything larger than 255 for a standard image is a bit silly, since the PHP color palette can only allocate 255 colors
// You can use more colors if you use imagecol
$iMax = 255 ;

// For the Newton fractal we need to work out when we have coverged
// The tolerance is set here, so when an iteration is within this tolerance of the previous iteration we stop
// Use tolerance squared to save some CPU time (sqrt() is expensive!)
// Note we need to converge before we know which root we approached
$tolerance  = 0.1 ;
$tolerance2 = $tolerance*$tolerance ;

// Now specify the roots
// It's faster CPU-wise to work out the function and its derivative yourself, but in general this isn't easy to do!
// CPU time should scale with the number of roots
// Each root is a complex number (ie a two value array) z = a+ib, (a,b)
// Just append the roots to the array of roots and the code will do the rest
$roots = array() ;

// Fibbonacci spiral
// Not very pretty, I just wanted to see something
//$roots[0] = array(  1,  0) ;
//$roots[1] = array(  0,  2) ;
//$roots[2] = array( -3,  0) ;
//$roots[3] = array(  0, -5) ;
//$roots[4] = array(  8,  0) ;
//$roots[5] = array(  0, 13) ;
//$roots[6] = array(-21,  0) ;
//$roots[7] = array(  0,-34) ;

// Spirally spiral
//for($i=0 ; $i<20 ; $i++){
//  $roots[$i] = complex_from_rtheta($i,$pi/10) ;
//}

// Cube roots of unity
// A very pretty fractal!
//$roots[0] = array( -0.5 ,  0.5*sqrt(3) ) ;
//$roots[1] = array(  1.0 ,         0.0  ) ;
//$roots[2] = array( -0.5 , -0.5*sqrt(3) ) ;

// Eight roots of unity
for($i=0 ; $i<8 ; $i++){
  $roots[$i] = complex_exponent( array(1,0) , $i/8 ) ;
}

//$roots[1] = array(  1.0 ,         0.0  ) ;
//$roots[2] = array( -0.5 , -0.5*sqrt(3) ) ;
//$roots[0] = array( -0.5 ,  0.5*sqrt(3) ) ;
//$roots[1] = array(  1.0 ,         0.0  ) ;
//$roots[2] = array( -0.5 , -0.5*sqrt(3) ) ;

// Same as above, but with more roots and different shapes
// Fourth roots of unity, and half their values
//$roots[0] = array(  1.0 ,  0.0 ) ;
//$roots[1] = array(  0.0 ,  1.0 ) ;
//$roots[2] = array( -1.0 ,  0.0 ) ;
//$roots[3] = array( -0.5 ,  0.0 ) ;
//$roots[4] = array(  0.0 , -1.0 ) ;
//$roots[5] = array(  0.5 ,  0.0 ) ;
//$roots[6] = array(  0.0 ,  0.5 ) ;
//$roots[7] = array(  0.0 , -0.5 ) ;

// 5th order real values
// What happens when roots lie on a line?  Not much really...
//$roots[0] = array( -2.0 ,  0.0 ) ;
//$roots[1] = array( -1.0 ,  0.0 ) ;
//$roots[2] = array(  0.0 ,  0.0 ) ;
//$roots[3] = array(  1.0 ,  0.0 ) ;
//$roots[4] = array(  2.0 ,  0.0 ) ;

// 5th order complex values
// A root at (0,0) doesn't have any problems (ie no division by zero or anything crazy)
//$roots[0] = array( -1.0 ,  0.0 ) ;
//$roots[1] = array(  1.0 ,  0.0 ) ;
//$roots[2] = array(  0.0 ,  0.0 ) ;
//$roots[3] = array(  0.0 ,  1.0 ) ;
//$roots[4] = array(  0.0 , -1.0 ) ;

// sin
// Quite a boring one
//$roots[0] = array( -2.0*$pi ,  0.0 ) ;
//$roots[1] = array( -1.0*$pi ,  0.0 ) ;
//$roots[2] = array(  0.0*$pi ,  0.0 ) ;
//$roots[3] = array(  1.0*$pi ,  0.0 ) ;
//$roots[4] = array(  2.0*$pi ,  0.0 ) ;

// Get polynomials for the function and its derivative from the roots
$f = complex_combine_roots($roots) ;
$fp = complex_differential($f) ;

// You can replace this with a hand calculated function and derivative for faster computation
// You can make use of the low and mid level complex functions below

/*
function f($z){
  global $f ;
  $sum = array(0,0) ;
  for($i=0 ; $i<count($f) ; $i++){
    $sum = complex_add($sum,complex_multiply($f[$i],complex_pow(1,$z,$i))) ;
    }
  return $sum ;
}
function fprime($z){
  global $fp ;
  $sum = array(0,0) ;
  for($i=0 ; $i<count($fp) ; $i++){
    $sum = complex_add($sum,complex_multiply($fp[$i],complex_pow(1,$z,$i))) ;
  }
  return $sum ;
}
*/

// Much faster version, but calculated by hand!:
// complex_pow($a,$z,$n) = az^n
// Add several complex numbers of complex_sum( array($z1, $z2...) )
// Multiply several complex numbers of complex_product( array($z1, $z2...) )
//function f     ($z){ return complex_add( complex_pow(1,$z,3) , array(-1,0) ) ; }
//function fprime($z){ return complex_pow(3,$z,2) ; }
//function f     ($z){ return complex_add( complex_pow(1,$z,3) , array(-1,0) ) ; }
//function fprime($z){ return complex_pow(6,$z,2) ; }
function f     ($z){ return complex_add( complex_pow(1,$z,8) , array(-1,0) ) ; }
function fprime($z){ return complex_pow(8,$z,7) ; }

// Use this for lots of colors: (high memory usage, so smaller possible number of pixels)
//$image = imagecreatetruecolor($width,$height) ;
// Use this for up to 255 colors (lower memory usage, so larger possible number of pixels)
$image = imagecreate($width,$height) ;

// Save some CPU time by doing these divisions once
$rX = ($xMax-$xMin)/$width  ;
$rY = ($yMax-$yMin)/$height ;

// Each pixel is colored according to the number of iterations and the root reached
// Change the color scheme by picking a palette
// 0, 1: rainbow colors (one ends in white, the other in black, for aesthetic reasons)
// 2-7: red, green, blue, cyan, magenta, yellow, ending to white
// 12-17: the same as above, but ending in black
// 22-27: hot colors (fade from color to white)
// 8: Shades of grey
// 9, 10, 11, 20, 21: various smooth gradients of different colors
// 100, 101: Rainbow colors, but on a log scale (101 is more sensitive to low iterations, 102 is more sensitive to high iterations)
// 102, 104, 105, 106: More crazy palettes! (102 should really be 22, but I ran out space in the indexing)

// For the best results, pick the palette based on the root that was reached
// Something like root%3 for 3 roots
// For very large numbers of roots you may need to intelligently arrange the order of the roots so that roots can share color palettes

// Now loop over all the pixels one at a time
for($row=0 ; $row<$height ; $row+=$size){
  for($col=0 ; $col<$width ; $col+=$size){
    $x = $xMin + $col*$rX ;
    $y = $yMax - $row*$rY ;
    $i = get_i(array($x,$y), $iMax) ;
    
    // $i is actually an array with lots of information:
    // $i[0]: which root was reached (-1 for not within tolerance of any root)
    // $i[1]: how many iterations it took
    // $i[2]: final value reached at the end of the iterations
    
    if($i[0]==-1){
      // Iterations that never reached roots
      $color = get_color($image,$i[1],$iMax,8) ;
    }
    else{
      // Some examples of how to pick colors based on roots
      //$color = get_color($image,$i[1],$iMax,$i[0]+12) ;
      //$color = get_color($image,$i[1],$iMax,2+($i[0]%3)) ;
      //$color = get_color($image,$i[1],$iMax, palette_picker($i[0])) ;
      $color = get_color($image,$i[1],$iMax,1) ;
    }
    imagefilledrectangle($image,$col,$row,$col+$size,$row+$size,$color) ;
  }
}

// Create image
header("Content-type: image/png") ;    
imagepng($image) ;
imagedestroy($image) ;

//////////////////////////////////////////////////////////////////////////////////////////
// High level complex functions
//////////////////////////////////////////////////////////////////////////////////////////
// Newton Raphson method
function newton_raphson($z){ return complex_subtract( $z , complex_divide(f($z),fprime($z))) ; }

// Get polynomial from roots
function complex_combine_roots($roots){
  $f = array(array(1,0)) ;
  for($i=0 ; $i<count($roots) ; $i++){ $f = complex_combine_terms($f,complex_multiply(array(-1,0),$roots[$i])) ; }
  return $f ;
}
function complex_differential($f){
  $diff = array() ;
  for($i=1 ; $i<count($f) ; $i++){ $diff[$i-1] = complex_multiply(array($i,0), $f[$i]) ; }
  return $diff ;
}
function complex_combine_terms($a, $b){
  // $b must be a linear function ie z-(x+iy)
  // $a is a polynomial in terms of (z-(x1+iy1))*(z-(x2+iy2))...
  $a_next = array() ;
  for($i=0 ; $i<=count($a) ; $i++){ $a_next[$i] = array(0,0) ; }
  for($i=0 ; $i<=count($a) ; $i++){
    $a_next[$i] = complex_add($a_next[$i], complex_multiply($a[$i], $b)) ;
    if($i!=0) $a_next[$i] = complex_add($a_next[$i], $a[$i-1]) ;
  }
  return $a_next ;
}

// Find out how many iterations for this pixel
function get_i($z, $iMax){
  global $tolerance2 ;
  $i = 0 ;
  $escape = false ;
  $root = -1 ;
  while($escape==false AND $i<$iMax) {
    $z1 = newton_raphson($z) ;
    if(distance2($z1,$z)<$tolerance2) $escape = true ;
    $z = $z1 ;
    $i++ ;
  }
  $root = find_root($z) ;
  return array( $root, $i, $z ) ;
}

// Work out which root (if any) the code converged to
// This one has a distance requirement
function find_root($z){
  global $roots ;
  $smallest_d2 = 1e6 ;
  $smallest_index = -4 ; // Grey palette
  for($i=0 ; $i<count($roots) ; $i++){
    $d2 = distance2($z,$roots[$i]) ;
    //if($d2<$smallest_d2 && $d2<$tolerance2){ // Distance requirement
    if($d2<$smallest_d2){ // Closest solution
      $smallest_d2 = $d2 ;
      $smallest_index = $i ;
    }
  }
  return $smallest_index ;
}
// This one just picks the closest root
function search_for_root($z){
  global $roots , $tolerance2 ;
  for($i=0 ; $i<count($roots) ; $i++){
    if(distance2($z,$roots[$i])<$tolerance2) return $i ;
  }
  return -1 ;
}

//////////////////////////////////////////////////////////////////////////////////////////
// Medium level complex functions
//////////////////////////////////////////////////////////////////////////////////////////
function complex_pow($a, $z,$n){ return complex_multiply(array($a,0), complex_exponent($z,$n) ) ; }
function complex_sum($zs){
  $sum = array(0,0) ;
  for($i=0 ; $i<count($zs) ; $i++){
    $sum[0] += $zs[$i][0] ;
    $sum[1] += $zs[$i][1] ;
  }
  return $sum ;
}
function write_complex_polynomial($f){
  for($i=0 ; $i<count($f) ; $i++){
    if($i>0) echo ' + ' ;
    echo '(' , $f[$i][0] , ',' , $f[$i][1] , ')z^' , $i ;
  }
  echo PHP_EOL ;
}

//////////////////////////////////////////////////////////////////////////////////////////
// Low level complex function
//////////////////////////////////////////////////////////////////////////////////////////
function complex_from_rtheta($r,$theta){ return array($r*cos($theta),$r*sin($theta)) ; }
function complex_add     ($z1,$z2){ return array( $z1[0]+$z2[0] , $z1[1]+$z2[1] ) ; }
function complex_subtract($z1,$z2){ return array( $z1[0]-$z2[0] , $z1[1]-$z2[1] ) ; }
function complex_multiply($z1,$z2){ return array( $z1[0]*$z2[0]-$z1[1]*$z2[1] , $z1[0]*$z2[1]+$z1[1]*$z2[0] ) ; }
function complex_divide  ($z1,$z2){
  $r2 = $z2[0]*$z2[0] + $z2[1]*$z2[1] ;
  if($r2==0) return array(0,0) ;
  return array( ($z1[0]*$z2[0]+$z1[1]*$z2[1])/$r2 , ($z1[1]*$z2[0]-$z1[0]*$z2[1])/$r2 ) ;
}

function distance2($z1, $z2){ return ($z1[0]-$z2[0])*($z1[0]-$z2[0])+($z1[1]-$z2[1])*($z1[1]-$z2[1]) ; }

function complex_exponent($z,$n){
  $r = sqrt($z[0]*$z[0]+$z[1]*$z[1]) ;
  $theta = atan2($z[1],$z[0]) ;
  $r = pow($r,$n) ;
  $theta = $theta*$n ;
  $z[0] = $r*cos($theta) ;
  $z[1] = $r*sin($theta) ;
  return $z ;
}
function complex_sin($z){ return array( sin($z[0])*0.5*(exp($z[1])+exp(-$z[1])) ,  cos($z[0])*0.5*(exp($z[1])-exp(-$z[1])) ) ; }
function complex_cos($z){ return array( cos($z[0])*0.5*(exp($z[1])+exp(-$z[1])) , -sin($z[0])*0.5*(exp($z[1])-exp(-$z[1])) ) ; }

?>

