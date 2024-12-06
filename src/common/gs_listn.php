
<html>
<head>

	<title> Skattning Data </title>

<style>

* {
	font-size: 26px;
}

p {
	font-size: 26px;
	margin-left: 15px;
}

body {
	margin-bottom: 75px;
	margin-left: 15px;
	margin-right: 260px;
	font-size: 28px;
	padding: 0px 10px;
	background-color: #ffffff;
}

.sw
{
    width: 450px;
}

</style>

</head>

<body>


<?php

include_once "connect.php";
include_once "getparam.php";
include_once 'stapel_disp.php';

function collect_grp($pid)
{
	global $emperator;
	
	$data = [];
	
	$query = "SELECT * FROM surv WHERE type=209 AND pers=$pid";
	$res = mysqli_query($emperator, $query);
	if ($res) while ($row = mysqli_fetch_array($res))
	{
		$sid = $row['surv_id'];
		
		$query2 = "SELECT * FROM data WHERE type=209 AND pers=$pid AND surv=$sid";
		$res2 = mysqli_query($emperator, $query2);
		if ($res2) while ($row2 = mysqli_fetch_array($res2))
		{
			$nam = $row2['value_c'];
			$val = $row2['value_a'];
			//if
			$data[$nam][] = $val;
		}
	}
	return $data;
}

function minmax($es)
{
	$res = [];
	foreach ($es as $key => $val)
	{
		$have = false;
		$min = $max = 0;
		foreach ($val as $v)
		{
			if (!$have)
			{
				$min = $max = $v;
				$have = true;
			} else {
				if ($v > $max) $max = $v;
				if ($v < $min) $min = $v;
			}
		}
		if ($have) {
			$res[$key]['max'] = $max;
			$res[$key]['min'] = $min;
		}
	}
	return $res;
}

function update_g(&$mmg, $gs)
{
	foreach ($gs as $key => $val)
	{
		$n = 0;
		$acc = 0;
		foreach ($val as $v)
		{
			++$n;
			$acc += $v;
		}
		if ($n)
			$mmg[$key]['grp'] = ($acc/$n);
	}
}

function index()
{
	$pid = getparam("pid");

	$es = collect_stapel_all($pid);
	
	$mmg = minmax($es);
	
	$gs = collect_grp($pid);
	
	update_g($mmg, $gs);
	
	echo "<table>\n";
	echo "<tr> <th> namn </th> <th> min </th> <th> max </th> <th> grp </th> </tr> \n";

	foreach ($mmg as $key => $val)
	{
		echo "<tr> <td> " . $key;
		echo "</td> <td> " . $val['min'];
		echo "</td> <td> " . $val['max'];
		echo "</td> <td> " . $val['grp'];
		echo "</td> </tr> \n";
	}
	
	echo "</table>\n";

}


index();

?>

</body>
</html>
