
<?php

include '../site/common/head.php';

echo <<<EOT

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
table tr td {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
}
</style>
EOT;

include '../site/common/common_php.php';
include '../site/common/connect.php';

$eol = "\n";

echo '</head><body>' . $eol;
echo '<br />' . $eol;
echo '<img width=50%  src="../site/common/logo.png" /> <br />';
echo '<br /> <br />' . $eol;

echo "<h1> Person Grupp Listning </h1> <br> <br>" . $eol;

$grp = [];

function for_discard($str)
{
	if (!$str) return true;
	if ($str == 'debug') return true;
	if ($str == 'test')  return true;
	if ($str == '')      return true;
	if ($str == 'null')  return true;
	return false;
}

$query = 'SELECT * FROM pers';
$res = mysqli_query($emperator, $query);
if ($res) while ($prow = mysqli_fetch_array($res))
{
	$n = $prow["name"];
	if (for_discard($n)) continue;
	$g = $prow["grupp"];
	if (for_discard($n)) continue;
	$f = array_search($g, $grp);
	if ($f === false)
		$grp[] = $g;
}

foreach ($grp as $g)
{
	echo $g . " <br>\n";
}

echo "<table>\n";

echo "<tr>";
	echo "<th> pid </th> \n";
	echo "<th> name </th> \n";
	echo "<th> email </th> \n";
	echo "<th> pnr </th> \n";
	echo "<th> grupp </th> \n";
echo "</tr>\n";

$query = 'SELECT * FROM pers';
$res = mysqli_query($emperator, $query);
if ($res) while ($prow = mysqli_fetch_array($res))
{
	$n = $prow["name"];
	if (for_discard($n)) continue;
	echo "<tr>\n";
	echo "<td> " . $prow['pers_id'] . " </td> \n";
	echo "<td> " . $n . " </td> \n";
	echo "<td> " . $prow['email'] . " </td> \n";
	echo "<td> " . $prow['pnr'] . " </td> \n";
	echo "<td> " . $prow['grupp'] . " </td> \n";
	echo "</tr>\n";
}

echo "</table>\n";


?>

</body>
</html>

