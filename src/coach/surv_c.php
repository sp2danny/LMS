
<?php

include "../site/common/getparam.php";
include "../site/common/connect.php";

$pid = getparam("pid");

$po = [];

$query = "SELECT * FROM pers WHERE pers_id='$pid'";
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res))
{
	$po['pid'] = $pid;
	$po['pnr'] = $row['pnr'];
	$po['name'] = $row['name'];
}


echo "data f&ouml;r person $pid <br>\n";

function tabulate($ao)
{
	$str = "<tr>";

	$i = array_key_first($ao);
	$fo = $ao[$i];

	$keys = [];

	foreach ($fo as $k => $v)
	{
		$str .= " <th> " . $k . " </th>";
		$keys[] = $k;
	}
	$str .= " </tr>";

	foreach ($ao as $o)
	{
		$str .= " <tr>";
		foreach ($keys as $k)
		{
			$str .= " <td>" . $o[$k] . " </td>";
		}
		$str .= " </tr>";
	}
	return $str;
}

if (count($po)) {
	echo "<hr> <table> \n";
	echo tabulate([$po]) . "\n";
	echo "</table> \n";
}

?>

