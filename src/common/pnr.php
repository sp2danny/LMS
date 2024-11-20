
<!DOCTYPE html>

<html>
<head>
	<title> PNR </title>

<?php

include_once 'connect.php';
include_once 'roundup.php';
include_once 'util.php';

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

echo <<<EOT
	<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		th, td {
			padding-top: 4px;
			padding-bottom: 4px;
			padding-left: 12px;
			padding-right: 12px;
		}
		body {
			margin-left: 75px;
			margin-bottom: 75px;
		}
	</style>
EOT;

echo "\n</head>\n";
echo "<body>\n";

echo "\t<br><br>\n";

echo "\t<table>\n";

echo "\t\t<tr>\n";

echo "\t\t\t<th> PNR </th> <th> Name </th> <th> Fixed </th> <th> Age </th> <th> Data </th> <th> PID </th>\n";

echo "\t\t</tr>\n";

$query = 'SELECT * FROM pers';
$res = mysqli_query($emperator, $query);
if ($res) while ($prow = mysqli_fetch_array($res)) {

	echo "\t\t<tr>\n";

	$pnr = $prow["pnr"];
	$nam = $prow["name"];
	$fix = pnr_fix($pnr);
	$age = "n/a";
	if (!$fix)
		$fix = "&lt;error&gt;";
	else
		$age = "2024" - substr($fix, 0, 4);

	$cnt = "n/a";
	$pid = $prow["pers_id"];

	$query2 = "SELECT COUNT(*) AS total FROM data WHERE pers='$pid'";
	$res2 = mysqli_query($emperator, $query2);
	if ($res2) {
		if ($prow2 = mysqli_fetch_assoc($res2)) {
			$cnt = $prow2['total'];
		} else {
			$cnt = "fetch failed";
		}
	} else {
		$cnt = "query failed";
	}


	echo "\t\t\t<td> $pnr </td> <td> $nam </td> <td> $fix </td> <td> $age </td> <td> $cnt </td> <td> $pid </td>\n";

	echo "\t\t</tr>\n";

}

echo "\t</table>\n";

?>

</body>
</html>

