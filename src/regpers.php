
<!DOCTYPE html>

<html>
<head> <title> Registrera </title> 
</head>
<body>

<?php

include 'php/connect.php';
include 'php/getparam.php';
include 'php/convert.php';

function ptbl($prow)
{
	echo '<table>';
	echo '<tr> <td> pid  </td> <td> ' . $prow[ 'pers_id' ] . '</td></tr>';
	echo '<tr> <td> date </td> <td> ' . $prow[ 'date'    ] . '</td></tr>';
	echo '<tr> <td> name </td> <td> ' . $prow[ 'name'    ] . '</td></tr>';
	echo '<tr> <td> pnr  </td> <td> ' . $prow[ 'pnr'     ] . '</td></tr>';
	echo '</table>';
}

$pnr = getparam('pnr');
$name = getparam('name');

$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
$res = mysqli_query($emperator, $query);

if ($row = mysqli_fetch_array($res)) {
	echo convert('Denna person fanns redan i databasen, ingen åtgärd utfördes') . " <br />";
	ptbl($row);
} else {
	$query = "INSERT INTO pers (name, pnr) VALUES ('" . $name . "', '" . $pnr . "');";
	echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
	$res = mysqli_query($emperator, $query);
	$ok = false;
	if ($res) {
		$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
		echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
		$res = mysqli_query($emperator, $query);
		if ($row = mysqli_fetch_array($res)) {
			$query = "INSERT INTO data (pers, type, value) VALUES (" . $row['pers_id'] . ", 1, 1);";
			echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
			$res = mysqli_query($emperator, $query);
			if ($res) {
				$ok = true;
				ptbl($row);
			}
		}
	}
	if ($ok) {
		echo convert('Infördes i databasen') . " <br />";
	} else {
		echo convert('Något gick fel') . " <br />";
	}
}


//echo convert('Nu är det klart') . " <br />";

echo '<br /><br /><a href="login.php"><button>Logga in</button></a>';

?>

</body></html>

