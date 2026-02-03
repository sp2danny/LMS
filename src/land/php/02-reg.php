
<?php

include "00-common.php";
include "00-connect.php";

$variant = getparam('variant', 0);

$name    = dirname($_SERVER['PHP_SELF']);
$email   = "variant-" . $variant;
$phone   = $_SERVER['REMOTE_ADDR'] ;

$query  = "INSERT INTO lead (name, email, phone) ";
$query .= "VALUES ('" . $name . "', '" . $email . "', '" . $phone . "')";

$result = mysqli_query($emperator, $query);

$last_id = mysqli_insert_id($emperator);


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

//if ($noskip)
//	$url = "03-intro.php?lid=$last_id";
//else
	$url = "04-land.php?lid=$last_id";

?> 

<!DOCTYPE html>

<html>
	<head>
		<?php
		//echo '<meta http-equiv = "refresh" content = ';
		//echo '"' . "0; URL='$url'" . '"' . " />" . "\n";
		?>

		<script>
			url = <?php echo "'" . $url . "'"; ?> ;

			if (screen.width <= 699) {
				document.location = "m." + url;
			} else {
				document.location = url;
			}
		</script>
	</head>

	<body>
	</body>
</html>


