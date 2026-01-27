
<?php

include_once "connect.php";
include_once "getparam.php";
include_once "debug.php";

$for = getparam("fr");
$by  = getparam("by");

$num  = getparam("id");
$val  = getparam("val");

$sid = false;

$query  = "SELECT * FROM surv WHERE name='group' AND type=$num AND pers=$for AND seq=$by";

//debug_log($query);

$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res)) {
	$sid = $row['surv_id'];
}

	$ok = true;

if ($sid === false)
{
	$query  = "INSERT INTO surv (name, type, pers, seq) ";
	$query .= "VALUES ('group', $num, $for, $by); ";

	$ok = true;

	//debug_log($query);
	$res = mysqli_query($emperator, $query);
	if (!$res) $ok = false;

	$sid = $emperator->insert_id;
}

$query  = "INSERT INTO data (pers, type, value_a, value_b, surv) ";
$query .= "VALUES ($for, $num, 1, $val, $sid); ";

//debug_log($query);

$res = mysqli_query($emperator, $query);
if (!$res) $ok = false;

if ($ok)
{
	echo "all ok";
} else {
	echo "something went wrong";
	debug_log("something went wrong");
	debug_log($emperator->error);
}


?>



