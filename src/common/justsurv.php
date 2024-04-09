
<?php

include_once 'connect.php';
include_once 'common.php';

echo "<html><head></head><body>\n";

$seqs = [];
$sids = [];

$pnr = getparam('pnr');
$pid = getparam('pid');
if ($pnr && ! $pid) {
	$query = "SELECT * FROM pers WHERE pnr='$pnr'";
	$res = mysqli_query($emperator, $query);
	if ($res) if ($row = mysqli_fetch_array($res)) {
		$pid = $row['pers_id'];
	}
}

$n = 0;

$query = "SELECT * FROM surv WHERE type='101' AND pers='$pid';";
$res = mysqli_query( $emperator, $query );
if ($res) while ($row = mysqli_fetch_array($res))
{
	$seqs[] = $row['seq'];
	$sids[] = $row['surv_id'];
	++$n;
}

echo "<hr>\n";

for ($i=0; $i<$n; ++$i)
{
	$sid = $sids[$i];
	$seq = $seqs[$i];
	
	echo "<a href='";
	echo "onesurv.php?sid=$sid";
	echo "&pid=$pid";
	echo "&seq=$seq";
	echo "'> $seq </a> <br> \n";
}

echo "<hr>\n";

echo $n . "<br> \n";

echo "<hr>\n";

echo "</body></html>\n";

?>

