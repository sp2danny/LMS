
<?php

include "00-common.php";
include "00-connect.php";

function runit()
{
	global $emperator;

	$num = getparam("num", 0);
	$bid = getparam("bid", 0);

	if ($num <= 0) return false;

	$query = "SELECT * FROM data WHERE pers='0' AND type='$num' AND value_b='$bid'";
	$res = mysqli_query($emperator, $query);
	
	if ($res && $row = mysqli_fetch_array($res)) {
		$query = "UPDATE data SET value_a=value_a+1 WHERE pers='0' AND type='$num' AND value_b='$bid'";
	} else {
		$query = "INSERT INTO data (pers, type, value_a, value_b) "
			. "VALUES ('0', '$num', '1', '$bid');";
	}

	$res = mysqli_query( $emperator, $query );

	return boolval($res);
}

runit();

?>
