
<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

function set_suv_val($i, $val, $lid)
{
	global $emperator;
	$query = "INSERT INTO data (pers, type, value_a, value_b, value_c, surv) "
		. "VALUES ('0', '56', '" . $i . "', '" . $val . "', 'lead', '" . $lid . "');";

	$res = mysqli_query( $emperator, $query );
	return boolval($res);
}


$leads = [];

$query = "SELECT * FROM lead";
$result = mysqli_query($emperator, $query);
if ($result) while ($row = mysqli_fetch_array($result))
{
	$idx = $row['lead_id'];
	$leads[$idx] = [];
	$leads[$idx]['index'] = $idx;
}

$aff = 0;

foreach ($leads as $key => $val)
{
	if (rand(1,100) < 10) {
		$lid = $val['index'];
		$base = rand(1, 40);
		for ($i=1; $i<=5; ++$i) {
			$r = $base + rand(1, 40);
			set_suv_val($i, $r, $lid);
		}
		++$aff;
	}
}

echo $aff . " rows affected.";

?>

