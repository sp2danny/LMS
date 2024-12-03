
<!-- inlude db_upd.php -->

<?php

include_once('connect.php');
include_once('getparam.php');
include_once('debug.php');
	
function index()
{
	global $emperator;

	$pid   = getparam("pid", false);
	$tp    = getparam('tp',  false);
	$val_a = getparam('a',   false);
	$val_b = getparam('b',   false);
	$val_c = getparam('c',   false);
	
	if (!$pid) return false;
	if (!$tp) return false;

	$query = "INSERT INTO data (type, pers";
	if ($val_a !== false) $query .= ", value_a";
	if ($val_b !== false) $query .= ", value_b";
	if ($val_c !== false) $query .= ", value_c";
	$query .= ") VALUES ($tp, $pid";
	if ($val_a !== false) $query .= ", $val_a";
	if ($val_b !== false) $query .= ", $val_b";
	if ($val_c !== false) $query .= ", '$val_b'";
	$query .= ");";

	debug_log($query);

	$res = mysqli_query($emperator, $query);
	
	return $res;
}

index();

?>
