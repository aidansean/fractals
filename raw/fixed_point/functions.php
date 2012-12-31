<?php

// Huge complicated (but hopefully fast!) calculation to get the color
// Different palettes give you different ranges of colors
// If $iMax is less than or equal to 255, and $i is integer you should not have any problems
function get_RGB($i,$iMax,$palette){
  $r = 0 ;
  $g = 0 ;
  $b = 0 ;
  $i = $i/$iMax ;
  switch($palette){
  case 2: // Red 
    $r = $i*255*1 ; $g = $i*255*0 ; $b = $i*255*0 ;
    break ;
  case 3: // Green 
    $r = $i*255*0 ; $g = $i*255*1 ; $b = $i*255*0 ;
    break ;
  case 4: // Blue 
    $r = $i*255*0 ; $g = $i*255*0 ; $b = $i*255*1 ;
    break ;
  case 5: // Cyan
    $r = $i*255*0 ; $g = $i*255*1 ; $b = $i*255*1 ;
    break ;
  case 6: // Magenta 
    $r = $i*255*1 ; $g = $i*255*0 ; $b = $i*255*1 ;
    break ;
  case 7: // Yellow
    $r = $i*255*1 ; $g = $i*255*1 ; $b = $i*255*0 ;
    break ;
  case 12: // Hot red
    if     ($i<1/2) { $r =       $i*255*2 ; $g =       $i*255*0 ; $b =       $i*255*0 ; }
    else if($i<2/2) { $r =          255*1 ; $g = ($i-1/2)*255*2 ; $b = ($i-1/2)*255*2 ; }
    break ;
  case 13: // Hot green
    if     ($i<1/2) { $r =       $i*255*0 ; $g =       $i*255*2 ; $b =       $i*255*0 ; }
    else if($i<2/2) { $r = ($i-1/2)*255*2 ; $g =          255*1 ; $b = ($i-1/2)*255*2 ; }
    break ;
  case 14: // Hot blue
    if     ($i<1/2) { $r =       $i*255*0 ; $g =       $i*255*0 ; $b =       $i*255*2 ; }
    else if($i<2/2) { $r = ($i-1/2)*255*2 ; $g = ($i-1/2)*255*2 ; $b =          255*1 ; }
    break ;
  case 15: // Hot cyan
    if     ($i<1/2) { $r =       $i*255*0 ; $g =       $i*255*2 ; $b =       $i*255*2 ; }
    else if($i<2/2) { $r = ($i-1/2)*255*2 ; $g =          255*1 ; $b =          255*1 ; }
    break ;
  case 16: // Hot magenta
    if     ($i<1/2) { $r =       $i*255*2 ; $g =       $i*255*0 ; $b =       $i*255*2 ; }
    else if($i<2/2) { $r =          255*1 ; $g = ($i-1/2)*255*2 ; $b =          255*1 ; }
    break ;
  case 17: // Hot yellow
    if     ($i<1/2) { $r =       $i*255*2 ; $g =       $i*255*2 ; $b =       $i*255*0 ; }
    else if($i<2/2) { $r =          255*1 ; $g =          255*1 ; $b = ($i-1/2)*255*2 ; }
    break ;
  case 22: // Very hot red
    $r =    255*1 ; $g = $i*255*1 ; $b = $i*255*1 ;
    break ;
  case 23: // Very hot green
    $r = $i*255*1 ; $g =    255*1 ; $b = $i*255*1 ;
    break ;
  case 24: // Very hot blue
    $r = $i*255*1 ; $g = $i*255*1 ; $b =    255*1 ;
    break ;
  case 25: // Very hot cyan
    $r = $i*255*1 ; $g =    255*1 ; $b =    255*1 ;
    break ;
  case 26: // Very hot magenta
    $r =    255*1 ; $g = $i*255*1 ; $b =    255*1 ;
    break ;
  case 27: // Very hot yellow
    $r =    255*1 ; $g =    255*1 ; $b = $i*255*1 ;
    break ;
  case 8: // Grey
    $r = $i*255*1 ; $g = $i*255*1 ; $b = $i*255*1 ;
    break ;
  case 9:  $r = $i*255*0 ; $g = 255*abs(sin($i*pi()*8)) ; $b = 255*abs(sin($i*pi()*8)) ;  break ; // Wave
  case 10: $r = 255*abs(sin($i*pi()*3)) ; $g = $i*255*0 ; $b = $i*255*0 ;  break ; // Pulse
  case 11: // Flame
    if     ($i>=0/3 AND $i<1/3) { $r=(3*$i-0)*255 ; $g=(3*$i-0)*255     ; $b=(3*$i-0)*255     ; } // Black to white   0/0-1/3
    else if($i>=1/3 AND $i<2/3) { $r=255          ; $g=255              ; $b=(1-(3*$i-1))*255 ; } // White to Yellow  1/3-2/3
    else if($i>=2/3 AND $i<3/3) { $r=255          ; $g=(1-(3*$i-2))*255 ; $b=0                ; } // Yellow to red    2/3-3/3
    else                        { $r=0            ; $g=0                ; $b=0                ; } // Black
    break ;
  case 12: // Log
    $i = log(1+$i) ;
    $r = $i*255*0 ; $g = $i*255*1 ; $b = $i*255*0 ;
    break ;
  case 20: // Aqua
    $r = $i*255*0 ; $g = 255*1 ; $b = (1-$i)*255*1 ;
    break ;
  case 21: // Cheese and onion
    $r = $i*255*1 ; $g = 255*1 ; $b = 255*0 ;
    break ;
  case 102: // Red to blue
    if     ($i>=0/2 AND $i<1/2) { $r=255              ; $g=(2*$i-0)*255     ; $b=(2*$i-0)*255 ; } // Red to white   0/0-1/2
    else                        { $r=(1-(2*$i-1))*255 ; $g=(1-(2*$i-1))*255 ; $b=255          ; } // White to Blue  1/2-2/2
    break ;
  case 103: // Fireworks
    $r=0 ; $g=0 ; $b=0 ;
    if($i>0.12  AND $i<0.13 ) { $r=205+10*abs($i-0.125) ; $g=0                    ; $b=0                    ; }
    if($i>0.245 AND $i<0.255) { $r=0                    ; $g=205+10*abs($i-0.25)  ; $b=0                    ; }
    if($i>0.37  AND $i<0.38 ) { $r=0                    ; $g=0                    ; $b=205+10*abs($i-0.375) ; }
    if($i>0.495 AND $i<0.505) { $r=205+10*abs($i-0.5)   ; $g=205+10*abs($i-0.5)   ; $b=0                    ; }
    if($i>0.62  AND $i<0.63 ) { $r=205+10*abs($i-0.625) ; $g=0                    ; $b=205+10*abs($i-0.625) ; }
    if($i>0.745 AND $i<0.755) { $r=0                    ; $g=205+10*abs($i-0.75)  ; $b=205+10*abs($i-0.75)  ; }
    if($i>0.87  AND $i<0.88 ) { $r=205+10*abs($i-0.875) ; $g=205+10*abs($i-0.875) ; $b=205+10*abs($i-0.875) ; }
    break ;
  case 104: // Roulette
    if     ($i>=0/10 AND $i<1/10)  { $r=0   ; $g=0 ; $b=0 ; }
	else if($i>=1/10 AND $i<2/10)  { $r=255 ; $g=0 ; $b=0 ; }
	else if($i>=2/10 AND $i<3/10)  { $r=0   ; $g=0 ; $b=0 ; }
	else if($i>=3/10 AND $i<4/10)  { $r=255 ; $g=0 ; $b=0 ; }
	else if($i>=4/10 AND $i<5/10)  { $r=0   ; $g=0 ; $b=0 ; }
	else if($i>=5/10 AND $i<6/10)  { $r=255 ; $g=0 ; $b=0 ; }
	else if($i>=6/10 AND $i<7/10)  { $r=0   ; $g=0 ; $b=0 ; }
	else if($i>=7/10 AND $i<8/10)  { $r=255 ; $g=0 ; $b=0 ; }
	else if($i>=8/10 AND $i<9/10)  { $r=0   ; $g=0 ; $b=0 ; }
	else if($i>=9/10 AND $i<10/10) { $r=255 ; $g=0 ; $b=0 ; }
	else                           { $r=0   ; $g=0 ; $b=0 ; }
	break ;
  case 105: // Contours
    $r=255 ; $g=255 ; $b=255 ;
    if($i>0.095 AND $i<0.105) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.195 AND $i<0.205) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.295 AND $i<0.305) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.395 AND $i<0.405) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.495 AND $i<0.505) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.595 AND $i<0.605) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.695 AND $i<0.705) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.795 AND $i<0.805) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.895 AND $i<0.905) { $r=0 ; $g=0 ; $b=0 ;}
    if($i>0.995 AND $i<1.005) { $r=0 ; $g=0 ; $b=0 ;}
    break ;
  default:
    if($palette>=1000){
      $r=0 ; $g=0 ; $b=0 ;
      if($iIn==$palette-1000) $r=255 ;
      break ;
    }
    if($palette==100){ // Low
      $i = pow($i,1/5) ;
    }
    if($palette==101){ // High
      $i = pow($i,5) ;
    }
    if     ($i>=0/7 AND $i<1/7) { $r=    (7*$i-0)*255 ; $g=0                ; $b=(7*$i-0)*255     ; } // Black to magenta 0/0-1/7
    else if($i>=1/7 AND $i<2/7) { $r=(1-(7*$i-1))*255 ; $g=0                ; $b=255              ; } // Magenta to blue  1/7-2/7
    else if($i>=2/7 AND $i<3/7) { $r=0                ; $g=(7*$i-2)*255     ; $b=255              ; } // Blue to cyan     2/7-3/7
    else if($i>=3/7 AND $i<4/7) { $r=0                ; $g=255              ; $b=(1-(7*$i-3))*255 ; } // Cyan to green    3/7-4/7
    else if($i>=4/7 AND $i<5/7) { $r=(7*$i-4)*255     ; $g=255              ; $b=0                ; } // Green to yellow  4/7-5/7
    else if($i>=5/7 AND $i<6/7) { $r=255              ; $g=(1-(7*$i-5))*255 ; $b=0                ; } // Yellow to red    5/7-6/7
    else if($i>=6/7 AND $i<7/7) { $r=255              ; $g=(7*$i-6)*255     ; $b=(7*$i-6)*255     ; } // Red to white     6/7-7/7
    else{
      if($palette==1){            $r=255              ; $g=255              ; $b=255              ; } // Pure as driven snow
      else           {            $r=0                ; $g=0                ; $b=0                ; } // Black, like my soul
    }
  }
  $rgb[0] = floor($r) ;
  $rgb[1] = floor($g) ;
  $rgb[2] = floor($b) ;
  return $rgb ;
}

// Try to allocate a color
// If you run out of colors use imagecreatetruecolor
function get_color($image,$i,$iMax,$palette){
  $rgb = get_RGB($i,$iMax,$palette) ;
  $color = imagecolorexact($image,$rgb[0],$rgb[1],$rgb[2]) ;
  if($color==-1){
    $color = imagecolorallocate($image,$rgb[0],$rgb[1],$rgb[2]) ;
    if($color == -1){
      $color = imagecolorclosest($image,$rgb[0],$rgb[1],$rgb[2]) ;
    }
  }
  return $color;
}

?>