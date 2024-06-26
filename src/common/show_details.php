
<!-- inlude show_details.php -->

<?php

include 'head.php';
include 'roundup.php';

echo <<<EOT

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

tr.lined {
  padding-left:   0px;
  padding-right:  0px;
  padding-top:    0px;
  padding-bottom: 0px;
  border: none;
}

table.lined {
  padding-left:   0px;
  padding-right:  0px;
  padding-top:    0px;
  padding-bottom: 0px;
  border: 1px solid;
  border-collapse: collapse;
}

td.lined {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
  border: 1px solid;
  text-align: center;
}

th.lined {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
  border: 2px solid;
  text-align: center;
}

.hr-lines:before {
  content: " ";
  display: block;
  height: 2px;
  width: 130px;
  position: absolute;
  top: 50%;
  left: 0;
  background: black;
}

.hr-lines {
  position: relative;
  max-width: 500px;
  margin: 100px auto;
  text-align: center;
}

.hr-lines:after {
  content:" ";
  height: 2px;
  width: 130px;
  background: black;
  display: block;
  position: absolute;
  top: 50%;
  right: 0;
}



</style>
EOT;


include 'common.php';
include 'connect.php';

$eol = "\n";

echo '</head><body>' . $eol;
echo '<br />' . $eol;
echo '<img width=50%  src="logo.png" /> <br />';
echo '<br /> <br />' . $eol;

function ptbl($prow, $mynt, $score=0)
{
	global $eol;
	echo '<table>' . $eol;
	echo '<tr> <td> Kundnummer    </td> <td> ' . $prow[ 'pers_id' ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> Guldmynt     </td> <td> ' . $mynt   . '</td></tr>' . $eol;
	echo '<tr> <td> Namn          </td> <td> ' . $prow[ 'name'    ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> Po&auml;ng   </td> <td> ' . $score  . '</td></tr>' . $eol;
	echo '<tr> <td> Personnummer  </td> <td> ' . $prow[ 'pnr'     ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> </td> <td> </td> </tr>' . $eol;
	echo '<tr> <td> Medlem sedan  </td> <td> ' . $prow[ 'date'    ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> </td> <td> </td> </tr>' . $eol;
	echo '</table>' . $eol;
}

function all()
{
	global $emperator, $eol;
	
	echo "<a href='create_report.php' > <button> Tillbaka </button> </a> <br> <br>" . $eol;


	$pid = getparam('pid');

	$query = "SELECT * FROM pers WHERE pers_id='" . $pid . "'";

	$res = mysqli_query($emperator, $query);
	$prow = false;
	$pnr = 0;
	$name = '';
	
	echo "<h2 class='hr-lines'> Persondata </h2>" . $eol;


	if ($prow = mysqli_fetch_array($res)) {

		$query = 'SELECT * FROM data WHERE pers=' . $prow['pers_id'] . ' AND type=4';
		$res = mysqli_query($emperator, $query);
		$mynt = 0;
		if ($row = mysqli_fetch_array($res))
			$mynt = $row['value_a'];

		ptbl($prow, $mynt);
		$pnr = $prow['pnr'];
		$name = $prow['name'];
	} else {
		echo convert('Denna person hittades inte i databasen.') . " <br />" . $eol;
		return;
	}
	

	echo "<h2 class='hr-lines'> Progress </h2>" . $eol;
	
	$alldata = roundup($pnr, $pid, $name);
	$atnum = 0;
	$block_name = "";
	$line_name = "";
	
	foreach ($alldata as $block) {
		if (!$block->someDone) continue;
		$atnum = $block->atnum;
		$block_name = $block->name;
		foreach ($block->lines as $line) {
			if($line->hasDone)
				continue;
			$line_name = $line->name;
			break;
		}
	}
	
	echo $block_name . ' - ' . $line_name . "<br><br>" . $eol;
	
	$segs = ['akta', 'positivitet', 'relevans', 'tillit', 'balans', 'omdome', 'motivation', 'goal', 'genomforande' ];

	echo "<h2 class='hr-lines'> Pulsmätning </h2>" . $eol;
	
	echo "<table class='lined' >";
	
	echo "<tr class='lined' > <th class='lined' > M&auml;tning </th> ";
	foreach($segs as $key => $entry)
		echo " <th class='lined' > " . $entry . ' </th> ';


	$wantout = false;
	for ($i=1; !$wantout; ++$i)
	{
		$nnn = 0;
		echo "<tr class='lined' > <td class='lined' >" . $i . " </td>";
		foreach($segs as $key => $entry)
		{
			$query = "SELECT * FROM surv WHERE pers='" . $pid . "'" . " AND type=7" .
					 " AND name='" . $entry . "' AND seq='" . $i . "'";
			$sid = 0;
			$res = mysqli_query($emperator, $query);
			if (!$res)
			{
				$err = 'DB Error, query surv --'.$query.'--';
				$wantout = true;
			} else {
				$prow = mysqli_fetch_array($res);
				if (!$prow) {
					$err = 'DB Error, fetch surv --'.$query.'--';
					$wantout = true;
				} else {
					$sid = $prow['surv_id'];
				}
			}
			
			$query = "SELECT * FROM data WHERE pers='" .$pid . "'" . " AND type=7" .
					 " AND surv='" . $sid . "'";
			$res = mysqli_query($emperator, $query);
			$num = 0; $sum = 0;
			if (!$res)
			{
				$err = 'DB Error, query data --'.$query.'--';
				$wantout = true;
			} else {
				
				while (true) {
					$prow = mysqli_fetch_array($res);
					if (!$prow) {
						break;
					} else {
						$num += 1;
						$sum += $prow['value_b'];
					}
				}
			}
			
			echo " <td class='lined' > ";
			if ($num>0) {
				echo number_format($sum / $num, 1);
				$nnn += 1;
			} else {
				echo '--';
			}
			echo " </td> ";
		}
		if ($nnn == 0) $wantout = true;
		echo " </tr> " . $eol;
	}

	echo " </table> <br><br>" . $eol;
	
	
	
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

	if ($have) {

		echo "<h2 class='hr-lines'> Disc </h2>" . $eol;

		$ret = "<img id='disc_mini' src='../common/minidisc.png' hidden=true /> \n";

		$ret .= " <table><tr> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td> <td>\n";

		$ret .= '<canvas id="disc_canvas_mini" width="99" height="99" style="border:1px solid #000000;">' ;
		$ret .= ' Din browser st&ouml;der inte canvas </canvas> ' . "\n";

		$ret .= "</td>";

		$ret .= "</tr></table> \n";

		$ret .=  '<script>';

		$ret .=  '  function rita_disc_mini() {';

		$ret .=  '  var c=document.getElementById("disc_canvas_mini"); ' . "\n";
		$ret .=  '  var ctx=c.getContext("2d"); ctx.fillStyle="#fff"; ' . "\n";
		$ret .=  '  var d2=document.getElementById("disc_mini");' . "\n";

		$ret .=  '  ctx.fillRect(0,0,99,99); ' . "\n";

		$ret .=  '  ctx.drawImage(d2,0,0);' . "\n";

		$ret .=  '  ctx.beginPath(); ' . "\n";
		$ret .=  '  ctx.fillStyle="#373"; ' . "\n";
		$ret .=  '  ctx.strokeStyle="#000"; ' . "\n";
		$ret .=  '  ctx.arc(';
		$ret .=  (99/2)+2.5*$LR ;
		$ret .=  ',';
		$ret .=  (99/2)+2.5*$UD ;
		$ret .=  ',2,0,2*Math.PI); ' . "\n";
		$ret .=  '  ctx.stroke(); ' . "\n";
		$ret .=  '  ctx.fill(); ' . "\n";
		$ret .=  '}' . $eol;
		$ret .=  '</script><br />' . "\n";

		$ret .=  '<img onload="rita_disc_mini()" src="../common/sq.png" /> <br />' . "\n" ;

		$ret .=  "<script> rita_disc(); </script> \n";

		echo $ret;
		
	}
	
	//echo "<hr>";
	
	$notes = [];
	
	$note_next = 1;
	
	while (true)
	{
		$query = "SELECT * FROM data WHERE pers='";
		$query .= $pid;
		$query .= "' AND type='20'";
		$query .= " AND value_a='" . $note_next . "'";

		$result = mysqli_query($emperator, $query);
		if ($result) if ($row = mysqli_fetch_array($result)) {
			$notes[] = $row['value_c'];
			$note_next +=1;
			continue;
		}
		break;
	}

	$mot = [];
	
	for ($qi=11; $qi<20; ++$qi) {
		$query = "SELECT * FROM data WHERE pers='" . $pid . "' AND type='" . $qi . "'";
		$res = mysqli_query($emperator, $query);
		$have = false;
		$date = 0;
		$tobj = "";
		if ($res) while ($row = mysqli_fetch_array($res)) {
			if (!$have) {
				$have = true;
				$tobj = $row['value_c'];
				$date = $row['date'];
			} else {
				$new_this = $row['value_c'];
				$new_date = $row['date'];
				if ($new_date > $date) {
					$tobj = $new_this;
					$date = $new_date;
				}
			}
		}
		if ($have) 
			$mot[] = $tobj;
	}

	if (!empty($mot)) {
		echo "<h2 class='hr-lines'> Motivatorer </h2>" . $eol;
		foreach ($mot as $key => $entry) {
			echo $entry . "<br>" . $eol;
		}
	}
	
	
	echo "<h2 class='hr-lines'> Anteckningar </h2>" . $eol;
	
	$first = true;
	foreach ($notes as $key => $entry)
	{
		if (!$first) echo "<hr>";
		echo $entry;
		echo "<br>" . $eol;
		$first = false;
	}
	if ($first) echo " &nbsp; &lt; inga anteckningar &gt; <br>" . $eol;

	echo "<br>Skapa Ny:";
	echo "<form action='create_note.php' >";
	
	echo "<input type='hidden' id='pid' name='pid' value='" . $pid . "' />" . $eol;
	
	echo "<input type='hidden' id='nn' name='nn' value='" . $note_next . "' />" . $eol;
	
	echo '<textarea id="note" name="note" rows="25" cols="50"> </textarea>';
	
	echo "<input type='submit' value='spara'>" . $eol;

	echo "</form>";
	
}

all();

?>

</body>
</html>








