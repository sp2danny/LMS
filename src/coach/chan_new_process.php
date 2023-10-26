
<html>
	
<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

$nn = getparam('name');
$pp = getparam('platser');
$dd = getparam('dagar');

$query  = "INSERT INTO data (type, pers, value_a, value_b, value_c) VALUES";
$query .= " (" . "'" . 70    . "'";
$query .= ", " . "'" . 0     . "'";
$query .= ", " . "'" . $pp   . "'";
$query .= ", " . "'" . $dd   . "'";
$query .= ", " . "'" . $nn   . "'" . ")";

$res = mysqli_query($emperator, $query);

if ($res) {
	echo <<<EOL
	<head>
	<meta http-equiv="Refresh" content="0; url='channel.php'" />
	</head>
EOL;
} else {
	echo <<<EOL
	<head></head><body>
	Något gick fel <br>
	<a href='channel.php'> <button> Tillbaka </button> </a>
	</body>
EOL;
}

?>

</html>
