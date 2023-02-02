
<?php

include "common.php";
include "connect.php";

$name    = getparam('name');
$email   = getparam('email');
$phone   = getparam('phone');

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
		echo '"' . "0; URL='intro.php?lid=" . $last_id . "'" . '"' . " />" . "\n";
		?>
	</head>

	<body>
	</body>
</html>


