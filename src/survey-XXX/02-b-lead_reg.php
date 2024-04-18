
<?php

include "00-common.php";
include "00-connect.php";

$name    = "special";
$email   = "special";
$phone   = "special";

$query  = "INSERT INTO lead (name, email, phone) ";
$query .= "VALUES ('" . $name . "', '" . $email . "', '" . $phone . "')";

$result = mysqli_query($emperator, $query);

$last_id = mysqli_insert_id($emperator);

$variant = getparam('variant', 0);

$query  = "INSERT INTO data (type, pers, value_a, value_b) ";
$query .= "VALUES ('17', '0', '$last_id', '$variant')";
$res = mysqli_query($emperator, $query);

$cid = 0;
$flags = 0;

$query = "SELECT * FROM data WHERE type=71 AND value_a=$variant";
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res)) {
	$cid = $row['value_b'];
}
if ($cid) {
	$query = "SELECT * FROM data WHERE type=70 AND data_id=$cid";
	$res = mysqli_query($emperator, $query);
	if ($res) if ($row = mysqli_fetch_array($res)) {
		$flags = $row['surv'];
	}
}

$noskip = boolval($flags & 1);

if ($noskip)
	$url = "03-intro.php?lid=$last_id";
else
	$url = "04-tratten.php?lid=$last_id";

?> 

<!DOCTYPE html>

<html>
	<head>
		<?php
		echo '<meta http-equiv = "refresh" content = ';
		echo '"' . "0; URL='$url'" . '"' . " />" . "\n";
		?>
	</head>

	<body>
	</body>
</html>


