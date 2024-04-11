
<?php

include_once "../../survey/php/00-common.php";
include_once "../../survey/php/00-connect.php";

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

class DataPoint
{
	public $vals;
	public $worst;
	public $wname;
	public $when;
}

$dps = [];

$styr = LoadIni("../../survey/styr.txt");

function getAndFill($sid, $seq)
{
	global $pid;
	global $emperator;
	global $styr;

	$vals = [];
	$w = 0;
	$wi = 0;

	$query = "SELECT * FROM data WHERE pers='$pid' AND type='101' AND surv='$sid';";
	$res = mysqli_query( $emperator, $query );
	if ($res) while ($row = mysqli_fetch_array($res))
	{
		$val = $row['value_b'];
		$vals[] = $val;
		if ($val > $w) {
			$w = $val;
			$wi = $row['value_a'];
		}
	}

	if (count($vals)<2) return false;

	$dp = new DataPoint;

	$dp->vals = $vals;
	$dp->worst = $w;
	$dp->wname = get_styr($styr, 'querys', "kat.$wi.name", 1);

	return $dp;
}

echo "<table>\n";

for ($i=0; $i<$n; ++$i)
{
	$sid = $sids[$i];
	$seq = $seqs[$i];
	
	$dp = getAndFill($sid, $seq);
	if ($dp) {
		$dps[] = $dp;

		echo "\t<tr>\n";

		echo "\t\t<td> ";
		echo "#" . $i+1;
		echo " </td>\n";

		echo "\t\t<td> ";
		echo $dp->worst;
		echo " </td>\n";

		echo "\t\t<td> ";
		echo $dp->wname;
		echo " </td>\n";

		echo "\t\t<td> ";

		$lnk = "onesurv.php?pid=$pid&sid=$sid&seq=$seq";

		echo "<a href='$lnk'> L&auml;nk </a>";
		echo " </td>\n";

		echo "\t</tr>\n";
	}

}

echo "</table>\n";

echo "<hr>\n";

echo $n . "<br> \n";

echo "<hr>\n";

echo "</body></html>\n";

?>

