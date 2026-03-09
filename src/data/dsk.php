
<?php

include_once "../common/getparam.php";
include_once "../common/connect.php";
include_once "../common/get_gr_val.php";

$data = [];

$pid = getparam('pid', false);

$data['egen'] = [];
$data['grupp'] = [];

$data['egen']['lr'] = 0;
$data['egen']['ud'] = 0;

if ($pid === false)
{
	echo json_encode($data);
	exit;
}

/*
	namn        type         a            b            c            surv
	==========  ===========  ===========  ===========  ===========  ===========
	disc        6            LR           UD
*/

$query1 = "SELECT * FROM data WHERE type=6 AND pers=$pid";

$have = false;
$when = 0;

$result1 = mysqli_query($emperator, $query1);
if ($result1) while ($row1 = mysqli_fetch_array($result1)) {
	if (!$have) {
		$LR = $row1['value_a'];
		$UD = $row1['value_b'];
		$have = true;
		$when = $row1['date'];
	} else {
		$date = $row1['date'];
		if ($date > $when) {
			$LR = $row1['value_a'];
			$UD = $row1['value_b'];
			$when = $date;
		}
	}
}

if ($have)
{
	$data['egen']['lr'] = $LR;
	$data['egen']['ud'] = $UD;
}

/*
	namn        type         a            b            c            surv
	==========  ===========  ===========  ===========  ===========  ===========

	gr-disc-lr  311          value        by
	gr-disc-ud  312          value        by
*/


$query = "SELECT * FROM data WHERE type=311 AND pers=$pid";
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res))
{
	$lr = $row['value_a'];
	$by = $row['value_b'];
	$query2 = "SELECT * FROM data WHERE type=312 AND pers=$pid AND value_b=$by";
	$res2 = mysqli_query($emperator, $query2);
	if ($res2) if ($row2 = mysqli_fetch_array($res2))
	{
		$ud = $row['value_a'];
		$obj = [];
		$obj['lr'] = $lr;
		$obj['ud'] = $ud;
		$data['grupp'][] = $obj;
	}
}

echo json_encode($data);


