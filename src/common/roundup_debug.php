
<!-- inlude roundup_debug.php -->

<?php

include 'head.php';
include 'roundup.php';
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

	$pnr = getparam('pnr');

	$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";

	$res = mysqli_query($emperator, $query);
	$prow = false;
	$pid = 0;
	$name = '';

	if ($prow = mysqli_fetch_array($res)) {

		$query = 'SELECT * FROM data WHERE pers=' . $prow['pers_id'] . ' AND type=4';
		$res = mysqli_query($emperator, $query);
		$mynt = 0;
		if ($row = mysqli_fetch_array($res))
			$mynt = $row['value_a'];

		ptbl($prow, $mynt);
		$pid = $prow['pers_id'];
		$name = $prow['name'];
	} else {
		echo convert('Denna person hittades inte i databasen.') . " <br />" . $eol;
		return;
	}

	$alldata = roundup($pnr, $pid, $name);
	$atnum = 0;

	echo "<ul>";
	foreach ($alldata as $bkey => $block) {
		echo "<li>";
		echo $block->name;
		echo "</li>";
		echo "<ul>";
		
		echo "<li>";
		echo "allDone : " . $block->allDone;
		echo "</li>";
		
		echo "<li>";
		echo "someDone : " . $block->someDone;
		echo "</li>";
		
		echo "<li>";
		echo "battNum : " . $block->battNum;
		echo "</li>";
		
		echo "<li>";
		echo "key : " . $bkey;
		echo "</li>";
		
		//echo "<li>";
		echo "<ul>";
		foreach ($block->lines as $lkey => $line)
		{
			echo "<li>";
			echo $line->name;
			echo "</li>";
			
			echo "<ul>";
			
			echo "<li>" . "key : " . $lkey . "</li>";
			echo "<li>" . "segIdx : " . $line->segIdx . "</li>";
			echo "<li>" . "isLink : " . $line->isLink . "</li>";
			echo "<li>" . "hasDone : " . $line->hasDone . "</li>";
			echo "<li>" . "always : " . $line->always . "</li>";

			echo "</ul>";
		}
		echo "</ul>";
		//echo "</li>";
		
		echo "</ul>";
	}
	echo "</ul>";

}

all();

?>

</body>
</html>








