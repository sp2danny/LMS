
<!-- inlude discdisplay.php -->

<?php

include_once 'connect.php';
include_once 'getparam.php';


function disc_draw($LR, $UD, $onclick = false)
{
	$ret = "<img id='Disc2' src='../common/Disc3-3.png' hidden=true /> \n";

	$ret .= " <table><tr> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td> <td>\n";

	$ret .= '<canvas id="discCanvas" width="850" height="850" style="border:1px solid #000000;" ';
	if ($onclick !== false)
		$ret .= "onclick='$onclick'";
	
	$ret .= ' >  Din browser st&ouml;der inte canvas </canvas> ' . "\n";

	$ret .= "</td>";
	$ret .= "</tr></table> \n";

	$ret .=  '<script>';

	$ret .=  'function rita_disc() {';

	$ret .=  '  var c=document.getElementById("discCanvas"); ' . "\n";
	$ret .=  '  var ctx=c.getContext("2d"); ctx.fillStyle="#fff"; ' . "\n";
	$ret .=  '  var d2=document.getElementById("Disc2");' . "\n";

	$ret .=  '  ctx.fillRect(0,0,850,850); ' . "\n";

	$ret .=  '  ctx.drawImage(d2,0,0);' . "\n";

	$ret .=  '  ctx.beginPath(); ' . "\n";
	$ret .=  '  ctx.fillStyle="#373"; ' . "\n";
	$ret .=  '  ctx.strokeStyle="#000"; ' . "\n";
	$ret .=  '  ctx.arc(' . "\n";
	$ret .=  (850/2)+17*$LR ;
	$ret .=  ',';
	$ret .=  (850/2)+17*$UD ;
	$ret .=  ',9,0,2*Math.PI); ' . "\n";
	$ret .=  '  ctx.stroke(); ' . "\n";
	$ret .=  '  ctx.fill(); ' . "\n";
	$ret .=  '}' . "\n";
	$ret .=  '</script><br />' . "\n";

	$ret .=  '<img onload="rita_disc()" src="../common/sq.png" /> <br />' . "\n" ;

	return $ret;
}

function get_disc($pid)
{
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
	
	if (!$have) return false;
	
	$res = [];
	$res['LR'] = $LR;
	$res['UD'] = $UD;
	
	return $res;
}

function discdisplay($pid, $onclick = false)
{
	$have = get_disc($pid);

	if ($have === false)
	{
		$LR = getparam("lr", "0");
		$UD = getparam("ud", "0");
	} else {
		$LR = $have['LR'];
		$UD = $have['UD'];
	}

	return disc_draw($LR, $UD, $onclick);
}

?>




