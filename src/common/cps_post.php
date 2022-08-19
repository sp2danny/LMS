
<!-- inlude gap_post.php -->

<html>
<head>

<?php

	include_once('connect.php');
	include_once('common.php');

	$pnr = getparam("pid", "0");
	
	// cps_post.php?pid=15&g1t=1&g1d=Akta&g2t=1&g2d=Akta&g3t=1&g3d=Akta&g4t=1&g4d=Akta&g5t=1&g5d=Akta&g6t=1&g6d=Akta&g7t=1&g7d=Akta
	

	function SetQ($pid, $a, $b, $c) {
		global $emperator;
		$err = true;
		$bb = getparam($b, 0);
		$cc = getparam($c, '');

		$query = "SELECT * FROM data WHERE type=9 AND pers='" . $pid . "' AND value_a='" . $a . "'";
		
		$done = false;
		$res = mysqli_query( $emperator, $query );
		if ($res) {
			$row = mysqli_fetch_array($res);
			if ($row) {
				$data_id = $row['data_id'];
				
				$query =  "UPDATE data";
				$query .= " SET value_b = " . $bb . ", value_c = '" . $cc . "'";
				$query .= " WHERE data_id = " . $data_id;
				$res = mysqli_query( $emperator, $query );
				if ($res)
					$done = true;
			}
		}

		if (!$done) {
			$query = "INSERT INTO data ";
			$query .= "( pers, type, value_a, value_b, value_c )" ;
			$query .= " VALUES ( " . $pid . ',' ;
			$query .= "9," . $a . ',' . $bb . ',' . "'" . $cc . "'" . ' )' ;

			if(!mysqli_query( $emperator, $query ))
			{
				$err = 'DB Error, query insert data --'.$query.'--';
			}
		}
		
		return $err;
	}
	
	$pid = getparam("pid", 0);
	for ($i=1; $i<=7; ++$i) {
		$ok = SetQ($pid, $i, "g" . $i . "t", "g" . $i . "d"); if ($ok!==true) echo $ok . "\n";
	}


?>

