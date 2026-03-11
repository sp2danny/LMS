
<?php

include_once "../common/getparam.php";
include_once "../common/connect.php";
include_once "../common/common_php.php";

$data = [];

$pid = getparam('pid', false);

$data['egen'] = [];
$data['grupp'] = [];

if ($pid === false)
{
	$data['egen'] = [0,0];
	$data['grupp'] = [0,0];
	echo json_encode($data);
	exit;
}

$data['egen'] [] = ROD( 'data', ['pers', 'type', 'value_a'], [$pid, 300, 1], 'value_b', 0 ) ;
$data['egen'] [] = ROD( 'data', ['pers', 'type', 'value_a'], [$pid, 300, 3], 'value_b', 0 ) ;

$num = 0;
$sum = 0;

$query = "SELECT * FROM data WHERE pers=$pid AND type=209 AND value_a=304 AND surv!=0";
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	$sum += $row['value_b'];
	++$num;
}

if ($num == 0)
	$data['grupp'] [] = 0;
else
	$data['grupp'] [] = ($sum/$num);


$num = 0;
$sum = 0;

$query = "SELECT * FROM data WHERE pers=$pid AND type=209 AND value_a=306 AND surv!=0";
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	$sum += $row['value_b'];
	++$num;
}

if ($num == 0)
	$data['grupp'] [] = 0;
else
	$data['grupp'] [] = ($sum/$num);



echo json_encode($data);

?>
