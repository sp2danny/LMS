
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

<?php

$BaseDomain = "mind2excellence.se/site/";


include_once 'main.js.php';
//include_once 'common.php';
?>

<style>
	th {
		background: #ddd;
	}
	td, tr, table {
		background: #fff;
	}
	th, td, tr, table {
		border-collapse: collapse; 
		padding-left: 8px;
		padding-right: 13px;
		padding-top: 3px;
		padding-bottom: 3px;
		border: 1px solid #eee;
	}
</style>


<script>
function doDraw(id)
{
	var cnv = document.getElementById(id);
	var ctx = cnv.getContext("2d");

	ctx.beginPath();
	ctx.rect(20, 20, 150, 100);
	ctx.fill();
}
</script>


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
	
	echo "<br /> <br /> <br /> \n";
	echo "<canvas id='cnv1' width='800px' height='800px' >\n";
	echo "</canvas>\n";
	
	echo "<script>\n";
	
	$nn = "var names = [";
	$v1 = "var val_1 = [";
	$v2 = "var val_2 = [";
	$v3 = "var val_3 = [";
	$frst = true;
	foreach ($mmg as $key => $val)
	{
		if (!$frst) {
			$nn .= ", ";
			$v1 .= ", ";
			$v2 .= ", ";
			$v3 .= ", ";
		}
		$frst = false;
		$nn .= "'" . $key . "'";
		$v1 .= $val['min'];
		$v2 .= $val['max'];
		$v3 .= $val['grp'];
	}
	$nn .= "];\n";
	$v1 .= "];\n";
	$v2 .= "];\n";
	$v3 .= "];\n";
	
	echo $nn;
	echo $v1;
	echo $v2;
	echo $v3;

	//echo "doDraw('cnv1');\n";

	echo "DrawSpider('cnv1', 9, val_2, names, val_1, val_3, '', 'spindel');\n";
	
	echo "</script>\n";

}


index();

?>

</body>
</html>
