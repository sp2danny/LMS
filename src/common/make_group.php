
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


function all()
{
	global $emperator, $eol;
	
	echo "<a href='create_report.php' > <button> Tillbaka </button> </a> <br> <br>" . $eol;

	
	echo "<h1> Skapa Grupper </h1> <br> <br>" . $eol;

	echo "<form action='create_group.php'>" . $eol;


	echo '<label for="grp"> Group: </label>';
	echo '<input type="text" id="grp" name="grp" >';
	echo '&nbsp;';

	echo "<table>" . $eol;

	$nn = 0;
	$query = 'SELECT * FROM pers';
	$res = mysqli_query($emperator, $query);
	if ($res) {
		while ($prow = mysqli_fetch_array($res)) {

			++$nn;
			echo "<tr><td>" . $eol;
			echo "<input type='checkbox' id='" . $prow["pnr"] . "' value='" .  $prow["pnr"] . "' name='" .  $nn . "' />" . $eol;
			echo "<label for='" . $prow["pnr"] . "'> " . $prow["name"];
			$grp = $prow["grupp"];
			if ($grp != "")
				echo " (" . $grp . ") ";
			echo " </label>" . $eol;

			echo "</td></tr>" . $eol;
		}
	}
	echo "</table>" . $eol;
	
	if ($nn > 0)
	{
		echo "<input type='hidden' id='max' name='max' value='" . $nn . "' />" . $eol;
		echo "<input type='submit' value='skapa och tilldela'>" . $eol;
		
	} else {
		echo "hittade ingen match <br>";
	}

	echo "</form>" . $eol;
}

all();

?>

</body>
</html>


