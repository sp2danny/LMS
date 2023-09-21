
<?php

include "getparam.php";

$emperator = mysqli_connect("mind2excellence.se.mysql", "mind2excellence_selms", "Gra55bben", "mind2excellence_selms");

if(!$emperator) { echo " DB connect failed <br /> \n "; return; }

$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, " . getparam("id") . ", 'checkout')";

$res = mysqli_query( $emperator, $query );

if (!$res) {
	echo "error";
} else {

	echo <<<END
		<html>
			<header>
				<title> Checkout </title>
			</header>
			<body>
				<p> Checkout </p>
			</body>
		</html>
END;

}

?>

