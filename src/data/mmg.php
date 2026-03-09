
<?php

include_once "../common/getparam.php";
include_once "../common/connect.php";
include_once "../common/stapel_disp.php";

$data = [];

$pid = getparam('pid', false);

$data['egen'] = [];
$data['grupp'] = [];
$data['utv'] = [];

if ($pid === false)
{
	$data['egen']  = [0,0,0];
	$data['grupp'] = [0,0,0];
	$data['utv']   = [0,0,0];
	echo json_encode($data);
	exit;
}

$num = 0;
$lo = 0;
$hi = 0;

// "motivation", "goal", "genomforande"

$args = [];
$args[] = "MMG";
$args[] = "1";
$args[] = "2";

$args[] = "motivation";
$args[] = "goal";
$args[] = "genomforande";

$dps = collect_stapel($pid, $args);

function add($nam)
{
	global $dps;
	global $data;

	$num = 0;
	$lo = 0;
	$hi = 0;

	$me = null;

	foreach ($dps as $dp)
	{
		if ($dp->name == $nam)
			$me = $dp;
	}
	
	foreach ($me->vals as $val)
	{
		if ($num == 0) {
			$lo = $val;
			$hi = $val;
		} else {
			if ($val > $hi) $hi = $val;
			if ($val < $lo) $lo = $val;
		}
		++$num;
	}

	$data['egen'][] = $hi;
	if ($num <=1)
		$data['utv'][] = 0;
	else
		$data['utv'][] = 100.0 * ($hi-$lo) / ($lo+1);
}

add('motivation');
add('goal');
add('genomforande');

function get_gr($rel, $nn)
{
	global $emperator;
	global $pid;

	$query2 = "SELECT * FROM data WHERE pers=$pid AND type=$nn";
	$res2 = mysqli_query($emperator, $query2);
	$num = 0;
	$sum = 0;
	if ($res2) while ($row2 = mysqli_fetch_array($res2))
	{
		++$num;
		$sk = $row2['value_a'];
		switch ($sk)
		{
			case 2:
				$val = $rel;
				break;
			case 1:
				$val = $rel-5;
				break;
			case -1:
				$val = $rel-10;
				break;
			case -2:
				$val = $rel-15;
				break;
			default:
				$val = $rel;
		}
		$sum += $val;
	}
	if ($num == 0) return 0;
	return $sum / $num;
}

$rel = $data['egen'][0];
$data['grupp'][] = get_gr($rel, 327);

$rel = $data['egen'][1];
$data['grupp'][] = get_gr($rel, 328);

$rel = $data['egen'][2];
$data['grupp'][] = get_gr($rel, 329);


echo json_encode($data);

?>
