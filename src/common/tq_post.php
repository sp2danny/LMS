
<!-- inlude tq_post.php -->

<html>
<head>

<?php

	include_once('connect.php');
	include_once('common.php');

	$pnr  = getparam("pnr", "0");
	$bnum = getparam('bnum', 0);
	$snum = getparam('snum', 0);

	$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";

	$pid = getparam("pid", "0");

	$err = false;
	
	if (!$bnum || !$snum)
		$err = "bnum snum error";

	$res = mysqli_query($emperator, $query);
	if (!$res)
	{
		$err = 'DB Error, query person --'.$query.'--';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			$err = 'DB Error, fetch person --'.$query.'--';
		} else {
			$pid = $prow['pers_id'];
		}
	}
	
	for ($qi=11; $qi<20; ++$qi) {
		$str = 'tq-' . $qi;
		$val = getparam($str, '');
		if (!empty($val)) {
			$query = "INSERT INTO data (";
			$query .= 'pers, type, value_c';
			$query .= ') VALUES (';
			$query .= $pid . ', ' . $qi . ', ' . '"' . $val . '"' . ')';
			$res = mysqli_query($emperator, $query);
			if (!$res) {
				$err = 'DB Error, insert --'.$query.'--';
				break;
			}
		}
	}
	
	$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $pid . ", 2, " . $bnum . ", " . $snum . ");";
	$res = mysqli_query($emperator, $query);


	if ($err === false) {
		
		$link = '../common/forward.php';
		$link .= '?pnr=' . $pnr ;
		$link .= '&bnum=' . $bnum ;
		$link .= '&snum=' . ($snum+1) ;

		echo '<meta http-equiv="refresh" content="0; URL=';
		echo $link;
		echo '" />';

		echo "</head>";
		echo "<body></body>";
		
	} else {

		echo "</head><body>";
		echo $err;
		echo "</body>";
	}

?>


</html>

