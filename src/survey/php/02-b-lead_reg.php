
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
$result = mysqli_query($emperator, $query);

?> 

<!DOCTYPE html>

<html>
	<head>
		<?php
		echo '<meta http-equiv = "refresh" content = ';
		echo '"' . "0; URL='04-tratten.php?lid=" . $last_id . "'" . '"' . " />" . "\n";
		?>
	</head>

	<body>
	</body>
</html>


