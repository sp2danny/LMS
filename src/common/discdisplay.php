
<!-- inlude discdisplay.php -->

<?php

// include('../common/connect.php');
// include('../common/common.php');

function discdisplay($pid)
{
	//$pid = getparam("pid", "0");
	
	global $emperator;

	$query1 = "SELECT * FROM data WHERE pers='";
	$query1 .= $pid;
	$query1 .= "' AND type='6'";
	
	$have = false;
	$when = 0;

	$result1 = mysqli_query($emperator, $query1);
	if ($result1) while ($row1 = mysqli_fetch_array($result1)) {
		if (!$have) {
			$LR = $row1['value_a'];
			$UD = $row1['value_b'];
			$have = true;
			$when = $row1['date'];
		} else {
			$date = $row1['date'];
			if ($date > $when) {
				$LR = $row1['value_a'];
				$UD = $row1['value_b'];
				$when = $date;				
			}
		}
	}

	if(!isset($LR))
	{
		$LR = getparam("lr", "0");
	}

	if(!isset($UD))
	{
		$UD = getparam("ud", "0");
	}

	$ret = "<img id='Disc2' src='../common/Disc3-3.png' hidden=true /> \n";

	$ret .= " <table><tr> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td> <td>\n";

	$ret .= '<canvas id="discCanvas" width="850" height="850" style="border:1px solid #000000;">' ;
	$ret .= ' Din browser st&ouml;der inte canvas </canvas> ' . "\n";

	$ret .= "</td>";
	//$ret .= "<td> <img src='../common/Disc1.png' \> </td>";
	$ret .= "</tr></table> \n";

	$ret .=  '<script>';

	$ret .=  '  function rita_disc() {';

	$ret .=  '  var c=document.getElementById("discCanvas"); ' . "\n";
	$ret .=  '  var ctx=c.getContext("2d"); ctx.fillStyle="#fff"; ' . "\n";
	$ret .=  '  var d2=document.getElementById("Disc2");' . "\n";

	$ret .=  '  ctx.fillRect(0,0,850,850); ' . "\n";

	$ret .=  '  ctx.drawImage(d2,0,0);' . "\n";

//echo 'ctx.beginPath(); ' . "\n";
//echo 'ctx.arc(250,250,90,0,2*Math.PI); ' . "\n";
//echo 'ctx.moveTo(100,0); ' . "\n";
//echo 'ctx.lineTo(100,200); ' . "\n";
//echo 'ctx.moveTo(0,100); ' . "\n";
//echo 'ctx.lineTo(200,100); ' . "\n";
//echo 'ctx.stroke(); ' . "\n";
	$ret .=  'ctx.beginPath(); ' . "\n";
	$ret .=  'ctx.fillStyle="#373"; ' . "\n";
	$ret .=  'ctx.strokeStyle="#000"; ' . "\n";
	$ret .=  'ctx.arc(' . "\n";
	$ret .=  (850/2)+12*$LR ;
	$ret .=  ',';
	$ret .=  (850/2)+12*$UD ;
	$ret .=  ',9,0,2*Math.PI); ' . "\n";
	$ret .=  'ctx.stroke(); ' . "\n";
	$ret .=  'ctx.fill(); ' . "\n";
	$ret .=  '}';
	$ret .=  '</script><br />' . "\n";

	$ret .=  '<img onload="rita_disc()" src="../common/sq.png" /> <br />' . "\n" ;

//echo '<img onload="Rita(';
//echo "'myCanvas'";
//echo ',' . $LR . ',' . $UN ;
//echo ')" src="sq.png" /> <br />' . "\n" ;

//echo $FullName . "<br>\n";

	// $ret .=  "<br><a href='../common/Disc2014.pdf'>Tolkning</a><br><br>\n";


	$ret .=  "<script> rita_disc(); </script> \n";

	return $ret;
}

?>




