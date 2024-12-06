
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
	if ($res) while($row = mysqli_fetch_array($res))
	{
		$sid = $row['surv_id'];
		
		$query2 = "SELECT * FROM data WHERE type=209 AND pers=$pid AND surv=$sid";
		$res2 = mysqli_query($emperator, $query2);
		if ($res2) while($row2 = mysqli_fetch_array($res2))
		{
			$nam = $row2['value_c'];
			$val = $row2['value_a'];
			//if
			$data[$nam][] = $val;
		}
	}
	return $data;
}

function index()
{
	$pid = getparam("pid");

	$es = collect_stapel_all($pid);

	var_dump($es);
	
	$gs = collect_grp($pid);

	var_dump($gs);

}


index();

?>


