<?php
$_GET['iMin'] = 1 ;
$_GET['iMax'] = 255 ;
$_GET['xMin'] = 0.41233408 ;
$_GET['xMax'] = 0.41237248 ;
$_GET['yMin'] = 0.14764032 ;
$_GET['yMax'] = 0.14767872 ;
$_GET['Jx'] = 0 ;
$_GET['Jy'] = 0 ;
$_GET['n']  = 2 ;
$_GET['palette'] = 7 ;
$_GET['small'] = -2 ;

$dx = ($_GET['xMax'] - $_GET['xMin'])/10 ;
$dy = ($_GET['yMax'] - $_GET['yMin'])/20 ;
$_GET['xMin'] += $dx ;
$_GET['xMax'] += $dx ;
$_GET['yMin'] += $dy ;
$_GET['yMax'] += $dy ;

include('mandelbrot.php') ;
?>
