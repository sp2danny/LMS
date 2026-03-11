
<?php

include_once "../common/getparam.php";
include_once "../common/connect.php";
include_once "../common/common_php.php";

$data = [];

$pid = getparam('pid', false);

$data['egen'] = [];
$data['grupp'] = [];
$data['list'] = [];

if ($pid === false)
{
	$data['egen'] = [0];
	$data['grupp'] = [0];
	$data['list'] = [""];
	echo json_encode($data);
	exit;
}

for ($i=1; $i<=5; ++$i)
{
	$query = "SELECT * FROM data WHERE pers=$pid AND type=303 AND value_a=$i";
	$res = mysqli_query($emperator, $query);
	if ($res) if ($row = mysqli_fetch_array($res))
	{
		$data['list'] [] = $row['value_c'];
	}
}

$data['egen'] [] = ROD( 'data', ['pers', 'type', 'value_a'], [$pid, 303, 0], 'value_b', 0 ) ;

$num = 0;
$sum = 0;

$query = "SELECT * FROM data WHERE pers=$pid AND type=209 AND value_a=303 AND surv!=0";
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
