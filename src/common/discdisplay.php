<html>
<head><title>Disc</title>

<?php

include('../common/connect.php');
include('../common/common.php');

echo "</head><body>";

$pid = getparam("pid", "0");

$query1 = "SELECT * FROM data WHERE pers='";
$query1 .= $pid;
$query1 .= "' AND type='6'";

echo $query1 . " <br>\n";

$result1 = mysqli_query($emperator,$query1);
if ($result1) {
	$row1 = mysqli_fetch_array($result1);
	$LR = $row1['value_a'];
	$UD = $row1['value_b'];
}

if(!isset($LR))
{
	$LR = getparam("lr", "0");
}

if(!isset($UD))
{
	$UD = getparam("ud", "0");
}

echo $LR . " " . $UD . " <br>\n";

echo "<img id='Disc2' src='Disc2.png' hidden=true /> \n";

echo "<table><tr><td>\n";


echo '<canvas id="myCanvas" width="500" height="500" style="border:1px solid #000000;">' ;
echo ' Din browser st&ouml;der inte canvas </canvas> ' . "\n";

echo "</td><td> <img src='Disc1.png' \> </td></tr></table> \n";



echo '<script>';

echo 'function rita_disc() {';

echo 'var c=document.getElementById("myCanvas"); ' . "\n";
echo 'var ctx=c.getContext("2d"); ctx.fillStyle="#fff"; ' . "\n";
echo 'var d2=document.getElementById("Disc2");';

echo 'ctx.fillRect(0,0,500,500); ' . "\n";

echo 'ctx.drawImage(d2,0,0);';

//echo 'ctx.beginPath(); ' . "\n";
//echo 'ctx.arc(250,250,90,0,2*Math.PI); ' . "\n";
//echo 'ctx.moveTo(100,0); ' . "\n";
//echo 'ctx.lineTo(100,200); ' . "\n";
//echo 'ctx.moveTo(0,100); ' . "\n";
//echo 'ctx.lineTo(200,100); ' . "\n";
//echo 'ctx.stroke(); ' . "\n";
echo 'ctx.beginPath(); ' . "\n";
echo 'ctx.fillStyle="#373"; ' . "\n";
echo 'ctx.strokeStyle="#000"; ' . "\n";
echo 'ctx.arc(' . "\n";
echo 250+8*$LR ;
echo ',';
echo 250+8*$UD ;
echo ',7,0,2*Math.PI); ' . "\n";
echo 'ctx.stroke(); ' . "\n";
echo 'ctx.fill(); ' . "\n";
echo '}';
echo '</script><br />' . "\n";

echo '<img onload="rita_disc()" src="sq.png" /> <br />' . "\n" ;

//echo '<img onload="Rita(';
//echo "'myCanvas'";
//echo ',' . $LR . ',' . $UN ;
//echo ')" src="sq.png" /> <br />' . "\n" ;

//echo $FullName . "<br>\n";

echo "<br><a href='Disc2014.pdf'>Tolkning</a><br><br>\n";

echo '<button type="button" onclick="Goto(' ;
echo "'create_or_load.php?pnr=" . $pid . "'" ;
echo ')"> Tillbaka </button>';

// echo '<br><br><button type="button" onclick="rita_disc()"> Rita igen </button>';

echo "<script> rita_disc(); </script> \n";

mysqli_close($emperator);

?>




</body>
</html>

