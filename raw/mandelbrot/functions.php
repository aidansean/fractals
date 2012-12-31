<?php 

$vars = array() ;
$vars['xMin'] =   -2 ;
$vars['xMax'] =    2 ;
$vars['yMin'] = -1.5 ;
$vars['yMax'] =  1.5 ;
$vars['iMin'] =    1 ;
$vars['iMax'] =  255 ;
$vars['n']       = 2 ;
$vars['palette'] = 0 ;
$vars['Jx'] = 0 ;
$vars['Jy'] = 0 ;
$width  = 375 ;
$height = 375 ;
$size   =   1 ;
$varKeys = array() ; $varsGET = array() ; $varsGallery = array() ;
$varKeys[] = 'small'   ; $varsGet[] = 0 ; $varsGallery[] = 0 ;
$varKeys[] = 'iMin'    ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'iMax'    ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'xMin'    ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'xMax'    ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'yMin'    ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'yMax'    ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'iMid'    ; $varsGet[] = 0 ; $varsGallery[] = 0 ;
$varKeys[] = 'yMid'    ; $varsGet[] = 0 ; $varsGallery[] = 0 ;
$varKeys[] = 'xWid'    ; $varsGet[] = 0 ; $varsGallery[] = 0 ;
$varKeys[] = 'yWid'    ; $varsGet[] = 0 ; $varsGallery[] = 0 ;
$varKeys[] = 'Jx'      ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'Jy'      ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'n'       ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'palette' ; $varsGet[] = 1 ; $varsGallery[] = 1 ;
$varKeys[] = 'r'       ; $varsGet[] = 0 ; $varsGallery[] = 0 ;
$varKeys[] = 'width'   ; $varsGet[] = 0 ; $varsGallery[] = 0 ;
$varKeys[] = 'height'  ; $varsGet[] = 0 ; $varsGallery[] = 0 ;

foreach(array_keys($_GET) as $key){ $vars[$key] = $_GET[$key] ; }
// Using 'small' for historic reasons
if($vars['small']==1 ) { $width =   125 ; $height =   125 ; }
if($vars['small']==-3) { $width = 10000 ; $height = 10000 ; }
if($vars['small']==-2) { $width =  5000 ; $height =  5000 ; }
if($vars['small']==-1) { $width =  1000 ; $height =  1000 ; }
//if($vars['small']==-1) { $width = 200 ; $height = 200 ; }
if(isset($vars['xWid']) && isset($vars['yWid'])){
  if($vars['xWid']*$vars['yWid']!=0){
    $vars['xMin'] = $vars['xMid']-$vars['xWid'] ;
    $vars['xMax'] = $vars['xMid']+$vars['xWid'] ;
    $vars['yMin'] = $vars['yMid']-$vars['yWid'] ;
    $vars['yMax'] = $vars['yMid']+$vars['yWid'] ;
  }
}

$dx = ($vars['xMax']-$vars['xMin'])/$width  ;
$dy = ($vars['yMax']-$vars['yMin'])/$height ;

function GETLink($names,$values){
  global $varKeys , $varsGet , $vars ;
  $Get = array() ;
  $request = '' ;
  for($i=0 ; $i<count($names) ; $i++){ $Get[$names[$i]] = $values[$i] ; }
  for($i=0 ; $i<count($varKeys) ; $i++){
    $key = $varKeys[$i] ;
    if(!$varsGet[$i]) continue ;
    if(!isset($Get[$key])) $Get[$key] = $vars[$key] ;
    $request = $request . '&amp;' . $key . '=' . $Get[$key] ;
  }
  return $request ;
}

function getI($x0,$y0){
  global $vars , $currentIMin, $currentIMax , $findIMinIMax ;
  $i  = 0 ;
  $x1 = 0 ;
  $y1 = 0 ;
  $x2 = 0 ;
  $y2 = 0 ;
  if($vars['n']==-2){
    $x1 = $x0 ;
    $y1 = $y0 ;
  }
  while($x1*$x1+$y1*$y1<=4 AND $i<$vars['iMax']) {
    if($findIMinIMax==1 AND $i>$currentIMin AND $currentIMax==$vars['iMax']) break ;
    switch($vars['n']){
    case -2:
      $x2 = $vars['Jx'] + $x1*$x1 - $y1*$y1 ;
      $y2 = $vars['Jy'] + 2*$x1*$y1         ;
    break ;
    case -1:
      $x2 = ($x1-$x0)*($x1-$x0)-$y1*$y1-$x0 ;
      $y2 = 2*($x1-$x0)*$y1-$y0 ;
    break ;
    case 2:
      $x2 = $x0 + $x1*$x1 - $y1*$y1 ;
      $y2 = $y0 + 2*$x1*$y1         ;
      break ;
    case 3:
      $x2 = $x0 + $x1*$x1*$x1 - 3*$x1*$y1*$y1 ;
      $y2 = $y0 - $y1*$y1*$y1 + 3*$x1*$x1*$y1 ;
      break ;
    }
	$x1 = $x2 ;
    $y1 = $y2 ;
    $i++ ;
    if($i<$currentIMin) $currentIMin = $i ;
    if($i>$currentIMax) $currentIMax = $i ;
  }
  return $i ;
}

function getIShip($x0,$y0){
  global $vars , $currentIMin, $currentIMax , $findIMinIMax ;
  $i  = 0 ;
  $x1 = 0 ;
  $y1 = 0 ;
  $x2 = 0 ;
  $y2 = 0 ;
  if($vars['n']==-2){
    $x1 = $x0 ;
    $y1 = $y0 ;
  }
  while($x1*$x1+$y1*$y1<=4 AND $i<$vars['iMax']) {
    if($findIMinIMax==1 AND $i>$currentIMin AND $currentIMax==$vars['iMax']) break ;
    $x1 = abs($x1) ;
    $y1 = abs($y1) ;
    $x2 = $x0 + $x1*$x1 - $y1*$y1 ;
    $y2 = $y0 + 2*$x1*$y1         ;
	$x1 = $x2 ;
    $y1 = $y2 ;
    $i++ ;
    if($i<$currentIMin) $currentIMin = $i ;
    if($i>$currentIMax) $currentIMax = $i ;
  }
  return $i ;
}

function getRGB($i,$palette){
  global $vars ;
  $r = 0 ;
  $g = 0 ;
  $b = 0 ;
  if($vars['iMax']==$vars['iMin']){
    $vars['iMin'] =   1 ;
    $vars['iMax'] = 255 ;
  }
  $iIn = $i ;
  $i = -$vars['iMin']/($vars['iMax']-$vars['iMin']) + $i/($vars['iMax']-$vars['iMin']) ;
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
    else
    {
      if($palette==1){            $r=255              ; $g=255              ; $b=255              ; } // Pure as driven snow
      else           {            $r=0                ; $g=0                ; $b=0                ; } // Black, like my soul
    }
  }
  $r = floor($r) ;
  $g = floor($g) ;
  $b = floor($b) ;
  $rgb['r'] = $r ;
  $rgb['g'] = $g ;
  $rgb['b'] = $b ;
  return $rgb ;
}

function getColor($image,$i,$palette){
  $rgb = getRGB($i,$palette) ;
  $color = imagecolorexact($image,$rgb['r'],$rgb['g'],$rgb['b']) ;
  if($color==-1){
    $color = imagecolorallocate($image,$rgb['r'],$rgb['g'],$rgb['b']);
    if($color == -1){
      $color = imagecolorclosest($image,$rgb['r'],$rgb['g'],$rgb['b']);
    }
  }
  return $color;
}

function printPalette($palette,$title){
  global $vars ;
  echo '        <td class="palette">' . PHP_EOL ;
  $link = GETLink(array('palette'),array($palette)) ;
  echo '          <a class="noBorder" href="index.php?palette=' . $palette . $link . '"><img class="border" src="palette.php?palette=' . $palette . '" width="256px" height="20px" alt="' . $title . '"/></a>' . PHP_EOL ;
  echo '        </td>' . PHP_EOL ;
}


?>