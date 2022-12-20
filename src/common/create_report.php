
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
	
	echo "<a href='make_group.php' > <button> Skapa Grupper ... </button> </a> <br> <br>" . $eol;

	echo "<a href='cleanup_db.php' > <button> Rensa Databas ... </button> </a> <br> <br>" . $eol;

	echo " <hr> " . $eol;

	echo "<h1> Skapa Rapport </h1> <br> <br>" . $eol;

	echo "<form action='create_report.php'>" . $eol;
	$grp = getparam("grp","*");
	echo '<label for="grp"> Group: </label>';
	echo '<input type="text" id="grp" name="grp" value="' . $grp . '" >';
	echo '&nbsp;';
	echo '<input type="submit" value="reload" >';
	echo '</form>' . $eol;

	echo "<form action='display_report.php'>" . $eol;
	echo "<table>" . $eol;

	$nn = 0;
	$query = 'SELECT * FROM pers';
	if ($grp!="*")
		$query .= " WHERE grupp='" . $grp . "'";
	$res = mysqli_query($emperator, $query);
	if ($res) {
		while ($prow = mysqli_fetch_array($res)) {

			++$nn;
			echo "<tr><td>" . $eol;
			echo "<input type='checkbox' id='" . $prow["pnr"] . "' value='" .  $prow["pnr"] . "' name='" .  $nn . "' />" . $eol;
			echo "<label for='" . $prow["pnr"] . "'> " . $prow["name"] ;
			$grp = $prow["grupp"];
			if ($grp!="null")
				echo " (" . $grp . ") ";
			echo " </label>" . $eol;

			echo "</td></tr>" . $eol;
		}
	}
	echo "</table>" . $eol;
	
	if ($nn > 0)
	{
		echo "<input type='hidden' id='max' name='max' value='" . $nn . "' />" . $eol;
		echo "<input type='submit'>" . $eol;
		
	} else {
		echo "hittade ingen match <br>";
	}

	echo "</form>" . $eol;
}

all();

?>

</body>
</html>


