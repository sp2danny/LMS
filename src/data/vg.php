
<?php

include_once "../common/getparam.php";
include_once "../common/connect.php";

$data = [];

$pid = getparam('pid', false);

$data['egen'] = [];
$data['grupp'] = [];
$data['utv'] = [];

if ($pid === false)
{
	echo json_encode($data);	
	exit;
}

$query = "SELECT * FROM data WHERE pers=$pid";

$data['egen'] = [66,77,88];
$data['grupp'] = [65,76,87];
$data['utv'] = [7,7,7];

echo json_encode($data);

?>
