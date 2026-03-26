
<?php

include_once "../common/getparam.php";
include_once "../common/connect.php";
include_once "../common/get_gr_val.php";

$data = [];

$pid = getparam('pid', false);

$data['vg'] = [];
$data['par'] = [];
$data['ato'] = [];
$data['mmg'] = [];
$data['dsk'] = [];

if ($pid === false)
{
	$data['vg']  = [0,0];
	$data['par'] = [0,0];
	$data['ato'] = [0,0];
	$data['mmg'] = [0,0];
	$data['dsk'] = [0,0];
	echo json_encode($data);
	exit;
}


/*
1 - värdegrund
2 - missionstatement
*/

$num = 0;
$lo = 0;
$hi = 0;

$query = "SELECT * FROM data WHERE pers=$pid AND type=201"; // vg
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	$val = $row['value_a'];
	if ($num == 0)
	{
		$lo = $val;
		$hi = $val;
	} else {
		if ($val > $hi) $hi = $val;
		if ($val < $lo) $lo = $val;
	}
	++$num;
}

$data['vg']['egen'][] = $hi;
/*if ($num <=1)
	$data['utv'][] = 0;
else
	$data['utv'][] = 100.0 * ($hi-$lo) / ($lo+1);
*/

$num = 0;
$lo = 0;
$hi = 0;

$query = "SELECT * FROM data WHERE pers=$pid AND type=202"; // ms
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	$val = $row['value_a'];
	if ($num == 0)
	{
		$lo = $val;
		$hi = $val;
	} else {
		if ($val > $hi) $hi = $val;
		if ($val < $lo) $lo = $val;
	}
	++$num;
}

$data['vg']['egen'][] = $hi;
//if ($num <=1)
//	$data['utv'][] = 0;
//else
//	$data['utv'][] = 100.0 * ($hi-$lo) / ($lo+1);



$data['vg']['grupp'] [] = get_gr_val_avg($pid, 201);
$data['vg']['grupp'] [] = get_gr_val_avg($pid, 202);

echo json_encode($data);

?>
