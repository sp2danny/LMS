<?php

include_once 'connect.php';
include_once 'getparam.php';
// include_once "debug.php";

function get_gr_val($by, $for, $num)
{
	//debug_log("get_gr_val");

	global $emperator;

	$query = "SELECT * FROM surv WHERE name='group' AND type=$num AND pers=$for AND seq=$by";
	//debug_log($query);
	$res = mysqli_query($emperator, $query);
	if (!$res) debug_log("no sid");
	if ($res) while ($row = mysqli_fetch_array($res)) {
		$sid = $row['surv_id'];
		$query2 = "SELECT * FROM data WHERE pers=$for AND type=$num AND surv=$sid";
		//debug_log($query2);
		$res2 = mysqli_query($emperator, $query2);
		if ($res2) if ($row2 = mysqli_fetch_array($res2))
			return $row2['value_b'];
	}
	return false;
}

function get_gr_val_avg($for, $num)
{
	global $emperator;
	$query2 = "SELECT * FROM data WHERE pers=$for AND type=$num AND surv!=0";
	$res2 = mysqli_query($emperator, $query2);
	$num = 0;
	$sum = 0;
	if ($res2) while ($row2 = mysqli_fetch_array($res2))
	{
		++$num;
		$sum += $row2['value_b'];
	}
	if ($num == 0) return 0;
	return $sum / $num;
}

?>

