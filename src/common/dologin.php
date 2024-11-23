
<!DOCTYPE html>

<html>
<head>

<?php

include_once 'connect.php';
include_once 'getparam.php';

function pnr_fix($pnr)
{
	$l = strlen($pnr);
	$p = strpos($pnr, "-");

	$f2 = substr($pnr, 0, 2);
	$lead = "19";
	if ($f2 < 24)
		$lead = "20";

	// 7211064634
	if (($l == 10) && ($p===false))
		return $lead . substr($pnr, 0, 6) . "-" . substr($pnr, 6);

	// 721106-4634
	if (($l == 11) && ($p==6))
		return $lead . $pnr;

	// 197211064634
	if (($l == 12) && ($p===false))
		return substr($pnr, 0, 8) . "-" . substr($pnr, 8);

	// 19721106-4634
	if (($l == 13) && ($p==8))
		return $pnr;

	return false;
}

$arg = getparam('pnr');
$fix = pnr_fix($arg);

$pid = 0;

$query = "SELECT * FROM pers WHERE pnr='$fix'";
$res = mysqli_query($emperator, $query);
if ($res) if ($prow = mysqli_fetch_array($res)) {

	$pid = $prow['pers_id'];
}




