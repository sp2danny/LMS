
<?php

include "00-common.php";
include "00-connect.php";

$name    = "special";
$email   = "special";
$phone   = "special";

$query  = "INSERT INTO lead (name, email, phone)";
$query .= "VALUES ('" . $name . "', '" . $email . "', '" . $phone . "')";

$result = mysqli_query($emperator, $query);

$last_id = mysqli_insert_id($emperator);

//	<meta http-equiv="refresh" content="0; URL='register.php'" />

?> 

<!DOCTYPE html>

<html>
	<head>
		<?php
		echo '<meta http-equiv = "refresh" content = ';
		echo '"' . "0; URL='03-intro.php?lid=" . $last_id . "'" . '"' . " />" . "\n";
		?>
	</head>

	<body>
	</body>
</html>


