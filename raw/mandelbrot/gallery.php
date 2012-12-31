<?php

header('Content-type: image/png') ;
$query = 'SELECT source FROM mandelbrot_gallery WHERE id=' . (str_replace("\"","\\\"",str_replace("\'","\\\'",$_GET['id']))) ;
$resource = mysql_query($query) ;
$result = mysql_fetch_assoc($resource) ;
echo $result['source'] ;

?>