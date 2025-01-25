
<!DOCTYPE html>

<html>
<head>
	<title> Grupp </title>
</head>
<body>

<?php

include_once 'connect.php';
include_once 'getparam.php';
include_once 'stapel_disp.php';

function discdisplay($pid)
{	
	if ($row = data_last("SELECT * FROM data WHERE pers='$pid' AND type='6'")) {
		$LR = $row['value_a'];
		$UD = $row['value_b'];
		return " " . $LR . ", " . $UD . " ";
	} else {
		return " -- inte gjort -- ";
	}
}

function vg($pid)
{
	if ($row = data_last("SELECT * FROM data WHERE pers='$pid' AND type='201'"))
		return $row['value_a'] . "&nbsp;%";
	else
		return "&nbsp;";
}

function ms($pid)
{
	if ($row = data_last("SELECT * FROM data WHERE pers='$pid' AND type='202'"))
		return $row['value_a'] . "&nbsp;%";
	else
		return "&nbsp;";
}

function arr2str($arr)
{
	$str = '[';
	$first = true;
	foreach ($arr as $val)
	{
		if (!$first) $str .= ",";
		$first = false;
		$str .= $val;
	}
	$str .= ']';
	return $str;
}

function dps2str($dps)
{
	$str = '{';
	$first = true;
	foreach ($dps as $dp)
	{
		if (!$first) $str .= ", ";
		$first = false;
		$str .= $dp->name;
		$str .= ":";
		$str .= arr2str($dp->vals);
	}
	$str .= '}';
	return $str;
}

function par($pid)
{
	$args = [];
	$args[] = "PÄR";
	$args[] = "1";
	$args[] = "2";

	$args[] = "positivitet";
	$args[] = "akta";
	//echo " <td> &nbsp; </td> \n";

	$args[] = "relevans";

	$dps = collect_stapel($pid, $args);
	return dps2str($dps);
}

function ato($pid)
{
	$args = [];
	$args[] = "ÄTO";
	$args[] = "1";
	$args[] = "2";

	$args[] = "akta";
	$args[] = "tillit";
	$args[] = "omdome";

	$dps = collect_stapel($pid, $args);
	return dps2str($dps);
}

function mmg($pid)
{
	$args = [];
	$args[] = "MMG";
	$args[] = "1";
	$args[] = "2";

	$args[] = "motivation";
	$args[] = "goal";
	$args[] = "genomforande";

	$dps = collect_stapel($pid, $args);
	return dps2str($dps);
}

function ant($pid)
{
	global $emperator;
	$ret = [];
	$ret['antal'] = 0;
	$ret['lista'] = [];
	$query = "SELECT * FROM surv WHERE type=209 AND pers=$pid";
	$res = mysqli_query($emperator, $query);
	if ($res) while($row = mysqli_fetch_array($res))
	{
		$ret['antal'] += 1;
		$ret['lista'][] = $row['seq'];
	}
	return $ret;
}

echo "\t<br><br>\n";

echo "\t<table>\n";

echo "\t\t<tr>\n";

echo "\t\t\t<th> PNR </th> <th> Name </th> <th> disc </th> <th> V.G. </th> <th> M.S. </th> ";
echo " <th> PÄR </th> <th> ÄTO </th> <th> MMG </th> <th> Ant </th> <td> Lst </td> \n";

//      positiv     äkta        relevant
//      ärlig       tillitsfull omdömesfull
//      motivation  målsättning genomförande 

echo "\t\t</tr>\n";

$grp = getparam('grp');

$query = "SELECT * FROM pers WHERE grupp='$grp'";
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	echo "\t\t<tr>\n";

	$for = $row["pers_id"];
	$pnr = $row["pnr"];
	$nam = $row["name"];
	$dsc = discdisplay($for);
	$vg  =  vg($for);
	$ms  =  ms($for);
	$par = par($for);
	$ato = ato($for);
	$mmg = mmg($for);
	$ant = ant($for);

	$vals = collect_stapel_all($for);
	$str = all2str($vals);

	$lst = true;

	if (strlen($str) <= 0) {
		$lst = false;
	}

	$ant = $ant['antal'];

	if ($ant < 2)
		$lst = false;

	echo "\t\t\t<td> $pnr </td> <td> $nam </td> <td> $dsc </td> <td> $vg </td> <td> $ms </td> ";
	echo " <td> $par </td> <td> $ato </td> <td> $mmg </td> \n";

	echo "\t\t\t<td> $ant </td> \n";
	
	if ($lst) {
		echo "\t\t\t<td> <a href='";
		echo "gs_listn.php?pid=$for";
		echo "'> listning </a> </td> \n";
	} else {
		echo "\t\t\t<td> &nbsp; </td> \n";
	}

	echo "\t\t</tr>\n";


}

echo "\t</table>\n";

?>

</body>
</html>




