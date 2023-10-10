
<!DOCTYPE html>

<html>
<head> <title> Registrera </title> 
</head>
<body>

<?php

include 'connect.php';
include_once 'getparam.php';
include_once 'convert.php';

$pnr = getparam('pnr');
$email = getparam('email');
$name = getparam('name');
$kid = getparam('kid');
$pwd = getparam('pwd');

$ok = true;
$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
$res = mysqli_query($emperator, $query);

if ($row = mysqli_fetch_array($res)) {
	echo convert('Denna person fanns redan i databasen, ingen åtgärd utfördes') . " <br />";
} else {
	$query = "INSERT INTO pers (pnr, pwd, name, email) VALUES ('" . $pnr . "', '" . $pwd . "', '" . $name . "', '" $email . "');";
	$res = mysqli_query($emperator, $query);
	$ok = $ok && $res;
	if ($res) {
		$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
		$res = mysqli_query($emperator, $query);
		if ($row = mysqli_fetch_array($res)) {
			$pid = $row['pers_id'];
			$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $pid . ", 1, 1, 1);";
			$res = mysqli_query($emperator, $query);
			$ok = $ok && $res;
			$query = "INSERT INTO data (pers, type, value_a) VALUES (" . $pid . ", 4, 0);";
			$res = mysqli_query($emperator, $query);
			$ok = $ok && $res;
			$query = "INSERT INTO data (pers, type, value_a) VALUES (" . $pid . ", 9, " . $kid . ");";
			$res = mysqli_query($emperator, $query);
			$ok = $ok && $res;
		}
	}
	if ($ok) {
		echo convert('Kontot skapat') . " <br />";
	} else {
		echo convert('Något gick fel') . " <br />";
	}
}

echo '<br /><br /><a href="login.php"><button>Logga in</button></a>';

?>

</body></html>

