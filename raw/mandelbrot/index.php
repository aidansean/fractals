<?php
// Connect to database
$mysql_host     = 'NOT_SET' ;
$mysql_username = 'NOT_SET' ;
$mysql_password = 'NOT_SET' ;
$mysql_database = 'NOT_SET' ;
$mySQL_connection = mysql_connect($mysql_host, $mysql_username, $mysql_password) or die('Could not connect: ' . mysql_error()) ;
mysql_select_db($mysql_database) or die('Could not select database') ;

include('functions.php') ;
if(isset($_GET['name'])){
  $name = str_replace("\"","\\\"",str_replace("\'","\\\'",$_GET['name'])) ;
  $query1 = 'name' ;
  $query2 = '"' . $name . '"' ;
  $query = '' ;
  for($i=0 ; $i<count($varKeys) ; $i++)
  {
    if(!$varsGallery[$i]) continue ;
    $query1 = $query1 . ',' . $varKeys[$i] ;
    $query2 = $query2 . ',"' . $vars[$varKeys[$i]] . '"' ;
  }
  $query = 'INSERT INTO mandelbrot_gallery (' . $query1 . ') VALUES (' . $query2 . ')' ;
  $result = mysql_query($query) ;
  header("Location:index.php?" . GETLink(array(),array())) ;
}
?>

<!--<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Mandelbrot explorer</title>
<script type="text/ecmascript">
var xmlhttp = GetXmlHttpObject() ;

<?php echo 'var request = "http://www.aidansean.com/mandelbrot/getIMinIMax.php?' . GETLink(array(),array()) . '" ;' . PHP_EOL ; ?>
function addUpdateColorsLink(){
  xmlhttp.onreadystatechange = updateLink ;  
  xmlhttp.open('GET', request + '&amp;sid=' + Math.random(),true) ;
  xmlhttp.send(null) ;
}
function updateLink(){
  if(xmlhttp.readyState==4){
    document.getElementById('updateColors').innerHTML = xmlhttp.responseText ;
  }
}

function GetXmlHttpObject(){
  if(window.XMLHttpRequest){
    // code for IE7+, Firefox, Chrome, Opera, Safari
    return new XMLHttpRequest() ;
  }
  if(window.ActiveXObject)
  {
    // code for IE6, IE5
    return new ActiveXObject("Microsoft.XMLHTTP") ;
  }
  return null ;
}

</script>
<link rel="stylesheet" type="text/css" title="Default style" href="style.css"/>
</head>
<body  onload="addUpdateColorsLink()">

<div id="layout">
  <div id="header"><h1>Mandelbrot explorer</h1></div>
  <div id="sidebar">
    <h2>Description</h2>
    <p><a href="http://en.wikipedia.org/wiki/Mandelbrot_set">The Mandelbrot set</a> is defined by a simple function in the complex plane.  Click on the image to zoom in.  Enjoy exploring the rich beauty of the set!</p>
    <h2>Coordinates</h2>
    <div class="center">
      <form action="" method="get">
        <table id="coordinates">
          <tbody>
            <tr><th class="coordinates">x<sub>min</sub></th><td><input name="xMin" value="<?php echo $vars['xMin'] ; ?>"/></td></tr>
            <tr><th class="coordinates">x<sub>max</sub></th><td><input name="xMax" value="<?php echo $vars['xMax'] ; ?>"/></td></tr>
            <tr><th class="coordinates">y<sub>min</sub></th><td><input name="yMin" value="<?php echo $vars['yMin'] ; ?>"/></td></tr>
            <tr><th class="coordinates">y<sub>max</sub></th><td><input name="yMax" value="<?php echo $vars['yMax'] ; ?>"/></td></tr>
            <tr><th class="coordinates">J<sub>x</sub></th><td><input name="Jx"     value="<?php echo $vars['Jx']   ; ?>"/></td></tr>
            <tr><th class="coordinates">J<sub>y</sub></th><td><input name="Jy"     value="<?php echo $vars['Jy']   ; ?>"/></td></tr>
          </tbody>
        </table>
        <div>
          <input type="hidden" name="iMin"    value="<?php echo $vars['iMin']    ; ?>"/>
          <input type="hidden" name="iMax"    value="<?php echo $vars['iMax']    ; ?>"/>
          <input type="hidden" name="n"       value="<?php echo $vars['n']       ; ?>"/>
          <input type="hidden" name="palette" value="<?php echo $vars['palette'] ; ?>"/>
          <input type="submit" value="Change coordinates" />
        </div>
      </form>
      
      <table id="compass">
        <tbody>
          <?php
            for($row=-1 ; $row<2 ; $row++){
              echo '<tr>' ;
              for($col=-1 ; $col<2 ; $col++){
              	
                if($row==0&&$col==0){ // Zoom out
                  $newXMin = ($vars['xMin']+$vars['xMax'])/2 - 2.5*($vars['xMax']-$vars['xMin']) ;
               	  $newXMax = ($vars['xMin']+$vars['xMax'])/2 + 2.5*($vars['xMax']-$vars['xMin']) ;
              	  $newYMin = ($vars['yMin']+$vars['yMax'])/2 - 2.5*($vars['yMax']-$vars['yMin']) ;
              	  $newYMax = ($vars['yMin']+$vars['yMax'])/2 + 2.5*($vars['yMax']-$vars['yMin']) ;
                }
                else{
                  $newXMin = $vars['xMin'] + $col*($vars['xMax']-$vars['xMin'])/10 ;
              	  $newXMax = $vars['xMax'] + $col*($vars['xMax']-$vars['xMin'])/10 ;
              	  $newYMin = $vars['yMin'] - $row*($vars['yMax']-$vars['yMin'])/10 ;
              	  $newYMax = $vars['yMax'] - $row*($vars['yMax']-$vars['yMin'])/10 ;
                }
                echo '<td class="compass"><a class="compass" href="?' . GETLink(array('xMin','xMax','yMin','yMax'),array($newXMin,$newXMax,$newYMin,$newYMax)) .'">' ;
                if($row==-1 AND $col==-1) echo '&#8598;' ;
                if($row==-1 AND $col==0 ) echo '&#8593;' ;
                if($row==-1 AND $col==1 ) echo '&#8599;' ;
                if($row==0  AND $col==-1) echo '&#8592;' ;
                if($row==0  AND $col==0 ) echo '<span id="zoomOut">Zoom<br />out</span>' ;
                if($row==0  AND $col==1 ) echo '&#8594;' ;
                if($row==1  AND $col==-1) echo '&#8601;' ;
                if($row==1  AND $col==0 ) echo '&#8595;' ;
                if($row==1  AND $col==1 ) echo '&#8600;' ;
                echo '</a></td>' . PHP_EOL ;
              }
              echo '</tr>' . PHP_EOL ;
            }
          ?>
        </tbody>
      </table>
      
      <a href="">Link to this view</a><br />
      <?php echo '<a href="mandelbrot.php?' . GETLink(array(),array()) . '">Link to big image</a><br />' ; ?>      
      <div id="updateColors">Update colors (please wait)</div>
      <a href="index.php">Reset</a>
      <h2>Save to gallery</h2>
      <?php echo '<img class="border" src="mandelbrot.php?small=1' . GETLink(array('small'),array(1)) . '" width="125px" height="125px" alt=""/><br />' . PHP_EOL ; ?>
      <form action="" method="get">
        <table id="saveToGallery">
          <tbody>
            <tr><th class="coordinates">Name</th><td><input name="name" value=""/></td></tr>
          </tbody>
        </table>
        <div>
<?php for($i=0 ; $i<count($varKeys) ; $i++){ if($varsGallery[$i]==1) echo '        <input type="hidden" name="' . $varKeys[$i] . '" value="' . $vars[$varKeys[$i]] . '"/>' . PHP_EOL ; } ?>
        <input type="submit" value="Save to gallery" />
        </div>
      </form>
    </div>
  </div>
  <div id="content">
    <div id="contentWrapper">
      <table>
        <tbody>
<?php
          for($row=0 ; $row<5 ; $row++){
            echo '          <tr>' . PHP_EOL ;
            for($col=0 ; $col<5 ; $col++){
              $newXMin = $vars['xMin']+($vars['xMax']-$vars['xMin'])*$col/5 ;
              $newXMax = $vars['xMin']+($vars['xMax']-$vars['xMin'])*($col+1)/5 ;
              $newYMin = $vars['yMax']-($vars['yMax']-$vars['yMin'])*($row+1)/5 ;
              $newYMax = $vars['yMax']-($vars['yMax']-$vars['yMin'])*$row/5 ;
              
              echo '            <td class="content"><a class="noBorder" href="?' . GETLink(array('xMin','xMax','yMin','yMax'),array($newXMin,$newXMax,$newYMin,$newYMax)) . '"><img class="noBorder" src="mandelbrot.php?small=1' . GETLink(array('xMin','xMax','yMin','yMax'),array($newXMin,$newXMax,$newYMin,$newYMax)) . '" width="125px" height="125px" alt=""/></a></td>' . PHP_EOL ;
            }
            echo '</tr>' . PHP_EOL ;
          }
        ?>
        </tbody>
      </table>
    </div>
    <div id="paletteContainer">
      <h2>Choose palette</h2>
      <table id="paletteSelector">
        <tbody>
          <tr>
            <?php printPalette( "0","Default") ;?>
            <?php printPalette( "1","Hot default") ;?>
          </tr>
          <tr>
            <?php printPalette( "2","Red") ;?>
            <?php printPalette("12","Hot red") ;?>
          </tr>
          <tr>
            <?php printPalette( "3","Green") ;?>
            <?php printPalette("13","Hot green") ;?>
          </tr>
          <tr>
            <?php printPalette( "4","Blue") ;?>
            <?php printPalette("14","Hot blue") ;?>
          </tr>
          <tr>
            <?php printPalette( "5","Cyan") ;?>
            <?php printPalette("15","Hot cyan") ;?>
          </tr>
          <tr>
            <?php printPalette( "6","Magenta") ;?>
            <?php printPalette("16","Hot magenta") ;?>
          </tr>
          <tr>
            <?php printPalette( "7","Yellow") ;?>
            <?php printPalette("17","Hot yellow") ;?>
          </tr>
          
          <tr>
            <?php printPalette("22","Very hot red") ;?>
            <?php printPalette("25","Very hot cyan") ;?>
          </tr>
          <tr>
            <?php printPalette("23","Very hot green") ;?>
            <?php printPalette("26","Very hot magenta") ;?>
          </tr>
          <tr>
            <?php printPalette("24","Very hot blue") ;?>
            <?php printPalette("27","Very hot yellow") ;?>
          </tr>
          <tr>
            <?php printPalette( "9","Wave") ;?>
            <?php printPalette("10","Pulse") ;?>
          </tr>
          <tr>
            <?php printPalette("11","Flame") ;?>
            <?php printPalette( "8","Grey") ;?>
          </tr>
          <tr>
            <?php printPalette("20","Aqua") ;?>
            <?php printPalette("21","Halogen") ;?>
          </tr>
          <tr>
            <?php printPalette("100","Low") ;?>
            <?php printPalette("101","High") ;?>
          </tr>
          <tr>
            <?php printPalette("102","Red white and blue") ;?>
            <?php printPalette("103","Fireworks") ;?>
          </tr>
          <tr>
            <?php printPalette("104","Roulette") ;?>
            <?php printPalette("105","Contours") ;?>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div id="fractalContainer">
    <h2>Choose fractal</h2>
    <table id="fractalSelector">
      <tbody>
        <tr>
          <td class="gallery">
            <a href="?n=2&amp;palette=<?php echo $vars['palette'] ; ?>"><img class="border" src="mandelbrot.php?small=1&amp;n=2&amp;palette=<?php echo $vars['palette'] ; ?>" width="125px" height="125px" alt=""/></a><br />
            <div class="center">Mandelbrot<sup>2</sup></div>
          </td>
          
          <td class="gallery">
            <a href="?n=3&amp;xMin=-1.5&amp;xMax=1.5&amp;palette=<?php echo $vars['palette'] ; ?>"><img class="border" src="mandelbrot.php?small=1&amp;n=3&amp;xMin=-1.5&amp;xMax=1.5&amp;palette=<?php echo $vars['palette'] ; ?>" width="125px" height="125px" alt=""/></a><br />
            <div class="center">Mandelbrot<sup>3</sup></div>
          </td>
          
          <td class="gallery">
            <a href="?n=-2&amp;xMin=-1.5&amp;xMax=1.5&amp;Jx=0.25&amp;Jy=0.52&amp;palette=<?php echo $vars['palette'] ; ?>"><img class="border" src="mandelbrot.php?small=1&amp;n=-2&amp;xMin=-1.5&amp;xMax=1.5&amp;Jx=0.25&amp;Jy=0.52&amp;palette=<?php echo $vars['palette'] ; ?>" width="125px" height="125px" alt=""/></a><br />
            <div class="center">Julia</div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="galleryContainer">
    <h2>Gallery</h2>
    <table id="gallery">
      <tbody>
<?php
      	$query = 'SELECT id FROM mandelbrot_gallery ORDER BY id DESC LIMIT 1' ;
      	$resource = mysql_query($query) ;
      	$result = mysql_fetch_assoc($resource) ;
      	$maxId = $result['id'] ;
        $counter = 0 ;
        $nCols = 6 ;
        $nToShow = 17 ;
        $galleryId = $maxId ;
        if(isset($_GET['galleryId'])) if(is_numeric($_GET['galleryId'])) $galleryId = str_replace("\"", "\\\"",str_replace("\'","\\\'",$_GET['galleryId'])) ;
        $query = 'SELECT * FROM mandelbrot_gallery WHERE id<' . ($galleryId+1) . ' ORDER BY CREATED DESC LIMIT ' . $nToShow ;
        $resource = mysql_query($query) ;
        while($row = mysql_fetch_assoc($resource))
        {
          if($counter%$nCols==0) echo '        <tr>' ;
          $link = '' ;    
          for($i=0 ; $i<count($varKeys) ; $i++){ if($varsGallery[$i]==1) $link = $link . '&amp;' . $varKeys[$i] . '=' . $row[$varKeys[$i]] ; }
          echo '          <td class="gallery"><a href="?' . $link . '"><img class="border" src="mandelbrot.php?small=1' . $link . '" width="125px" height="125px" alt="' . $row['name'] . '"/></a><br /><div class="center">&nbsp;' . $row['name'] . '&nbsp;</div></td>' . PHP_EOL ;
          if(($counter+1)%$nCols==0) echo '</tr>' . PHP_EOL ;
          $counter++ ;
          if($counter==$nCols) $counter = 0 ;
        }
        if($galleryId-$nToShow<0) $galleryId = $maxId ;
        echo '          <td><div class="center"><a href="index.php?galleryId=' . ($galleryId-$nToShow) . GETLink(array(),array()) . '">More...</a></div></td></tr>' . PHP_EOL ;
      ?>
      </tbody>
    </table>
  </div>
  <div>
  <h2>About the fractals</h2>
  <div id="aboutFractals">
    <p>The fractals shown here are all related to the famous Mandelbrot fractal set.  The mapping is very simple:</p>
    <p class="equation">z<sub>n+1</sub>&nbsp;=&nbsp;z<sub>n</sub><sup style="position:relative;left:-0.5em">2</sup>+&nbsp;p</p>
    
    <p>Any points in the complex plane that do not diverge are in the set.  In this representation the algorithm is iterated up to 255 times.  If the point has not diverged (|r|>2) after 255 iterations the point is considered to be in the set.  If the point diverges before 255 iterations it is coloured according to the number of iterations at which it diverged.  In the default palette the "colder" colours indicate a fast divergence and the "hotter" colours indicate a slower divergence.</p>
    
    <p>The Mandelbrot<sup>3</sup> set is the same as the Mandelbrot set except the algorithm uses a cube instead of a square:</p>
    <p class="equation">z<sub>n+1</sub>&nbsp;=&nbsp;z<sub>n</sub><sup style="position:relative;left:-0.5em">3</sup>+&nbsp;p</p>

    <p>The Julia set is closely related to the Mandelbrot except a constant number (J<sub>x</sub>+iJ<sub>y</sub>) is added in each iteration.  Changing the values of J<sub>x</sub> and J<sub>y</sub> can lead to dramatic changes in the fractal's shape.</p>
    <p class="equation">z<sub>n+1</sub>&nbsp;=&nbsp;z<sub>n</sub><sup style="position:relative;left:-0.5em">2</sup>+&nbsp;c</p>
    <p class="equation">c&nbsp;=&nbsp;J<sub>x</sub>&nbsp;+&nbsp;i J<sub>y</sub></p>

    <p>This algorithm uses the "escape time" algorithm, where the colour palette is divided into discrete steps.  This can lead to very obvious contours in the respresentation, which can be visually appealing or distracting, depending on the zoom and coordinates of the image, as well as your personal taste.  The images tend to break as you zoom further in, because there are limits to how reasonably precise the coordinates can be specified.</p>
      </div>
    </div>
  <div id="footer">
    &copy; 2010 <a href="http://www.aidansean.com/">Aidansean</a> / CSS3 valid / XHTML 1.0 Strict valid<br />
    <a href="http://jigsaw.w3.org/css-validator/check/referer">
      <img style="border:0;width:88px;height:31px"
        src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
        alt="Valid CSS!" /></a>
    <a href="http://validator.w3.org/check?uri=referer">
      <img style="border:0;width:88px;height:31px"
        src="http://www.w3.org/Icons/valid-xhtml10-blue"
        alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>

     </div>
  <!-- Design inspired by http://www.thesitewizard.com/css/design-2-column-layout.shtml -->
</div>

</body>
</html>