
<!-- inlude delete_user.php -->

<?php

include 'head.php';
include 'roundup.php';

echo <<<EOT

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
table tr td {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
  border: 1px solid;
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
	
	echo "<h1><font color='red'><blink> VARNING </blink></font></h1><br>" . $eol;

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
	
	echo "<h1><font color='red'><blink> VARNING </blink></font></h1><br>" . $eol;
		
	$segs = ['akta', 'positivitet', 'relevans', 'tillit', 'balans', 'omdome', 'motivation', 'goal', 'genomforande' ];
	
	echo "<table>";
	
	echo "<tr> <th> M&auml;tning </th> ";
	foreach($segs as $key => $entry)
		echo ' <th> ' . $entry . ' </th> ';


	$wantout = false;
	for ($i=1; !$wantout; ++$i)
	{
		$nnn = 0;
		echo "<tr> <td>" . $i . " </td>";
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
			
			echo " <td> ";
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
	
	echo "<h1><font color='red'><blink> VARNING </blink></font></h1><br>" . $eol;

	echo "<a href='actually_delete.php?pid=" . $pid . "'> <button> Ta bort </button> </a>";
	
	
}

all();

?>

</body>
</html>








