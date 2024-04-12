
<?php

include_once "../../survey/php/00-common.php";
include_once "../../survey/php/00-connect.php";

class DataPoint
{
	public $vals;
	public $worst;
	public $wname;
	public $when;
}


function DrawData($dps, $to)
{
	$n = count($dps);

	$str = "    ['" . $title . "'";
	for ($i=0; $i<$n; ++$i) {
		$str .= ", '" . $dps[$i]->wname . "'";
	}
	$str .= "],\n";

	$str .= "      ['" . $m . "'";

	for ($i=0; $i<$n; ++$i) {
		if ($i > 0)
			$str .= ", ";
		$str .= $dps[$i]->worst;
	}

	$str .= "]";

	$to->startTag('script');
	$to->regLine("google.charts.load('current', {'packages':['bar']});");
	$to->regLine("google.charts.setOnLoadCallback(drawChart_bar_" . $num . ");");
	$to->regLine("function drawChart_bar_" . $num . "() {");
	$to->regLine("  var data = google.visualization.arrayToDataTable([");
	$to->regLine($str);
	$to->regLine('  var options = {');
	$to->regLine('    title: "' . $title . '",');
	$to->regLine('    width: 450,');
	$to->regLine('    legend: { position: "none" },');
	$to->regLine('    chart: { title: "' . "" . '",'); // $title
	$to->regLine('             subtitle: "" },');
	$to->regLine('    bars: "horizontal", // Required for Material Bar Charts.');
	$to->regLine('    axes: {');
	$to->regLine('      x: {');
	$to->regLine('        0: { side: "top", label: "Percentage"} // Top x-axis.');
	$to->regLine('      }');
	$to->regLine('    },');
	$to->regLine('    bar: { groupWidth: "90%" }');
	$to->regLine('  };');
	$to->regLine('  var chart = new google.charts.Bar(document.getElementById("bar_chart_' . $num . '"));');
	$to->regLine('  chart.draw(data, options);');
	$to->regLine("}");
	$to->stopTag("script");
	$to->regLine('<div id="bar_chart_' . $num . '" style="width: 450px; height: 250px"></div>');

	return true;
}

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

