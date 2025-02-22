
<?php

include_once "connect.php";
include_once "getparam.php";
include_once "debug.php";

$for = getparam("fr");
$by  = getparam("by");

$num  = getparam("id");
$val  = getparam("val");

$sid = false;

$query  = "SELECT * FROM surv WHERE name='group' AND type=209 AND pers=$for AND seq=$by";

debug_log($query);

$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res)) {
	$sid = $row['surv_id'];
}

if ($sid === false)
{
	$query  = "INSERT INTO surv (name, type, pers, seq) ";
	$query .= "VALUES ('group', 209, $for, $by); ";

	$ok = true;

	$res = mysqli_query($emperator, $query);
	if (!$res) $ok = false;

	$sid = $emperator->insert_id;
}

$query  = "INSERT INTO data (pers, type, value_a, value_b, surv) ";
$query .= "VALUES ($for, 209, $num, $val, $sid); ";

debug_log($query);

$res = mysqli_query($emperator, $query);
if (!$res) $ok = false;

if ($ok)
{
	echo "all ok";
} else {
	echo "something went wrong";
}


?>



