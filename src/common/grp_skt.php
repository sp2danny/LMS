
<?php

include_once "connect.php";
include_once "getparam.php";

$for = getparam("for");
$by  = getparam("by");

$query  = "INSERT INTO surv (name, type, pers, seq) ";
$query .= "VALUES ('group', 209, $for, $by); ";

$ok = true;

$res = mysqli_query($emperator, $query);
if (!$res) $ok = false;

$par = array_merge($_GET, $_POST);

$sid = $emperator->insert_id;

foreach ($par as $key => $val)
{
	if ($key=='for') continue;
	if ($key=='by') continue;
	
	$query  = "INSERT INTO data (pers, type, value_a, surv, value_c) ";
	$query .= "VALUES ($for, 209, $val, $sid, '$key'); ";
	
	$res = mysqli_query($emperator, $query);
	if (!$res) $ok = false;
	
}

if ($ok)
{
	echo "all ok";
} else {
	echo "something went wrong";
}

	
?>



