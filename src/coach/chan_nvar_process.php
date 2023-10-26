
<html>
	
<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

$cid = getparam('cid');
$var = getparam('var');
$comm = getparam('comm');

$query  = "INSERT INTO data (type, pers, value_a, value_b, value_c) VALUES";
$query .= " (" . "'" . 71    . "'";
$query .= ", " . "'" . 0     . "'";
$query .= ", " . "'" . $var   . "'";
$query .= ", " . "'" . $cid  . "'";
$query .= ", " . "'" . $comm . "'" . ")";

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
