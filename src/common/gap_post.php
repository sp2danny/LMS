
<!-- inlude gap_post.php -->

<html>
<head>

<?php

	include('connect.php');
	include('common.php');
	
	// gap_post.php?pnr=5906195697&gap-name=motiv&gap-num=1&gap-cnt=5&q1=22&q2=54&q3=18&q4=55&q5=31

	$pnr = getparam("pnr", "0");
	$gapName = getparam('gap-name', "");
	$gapNum = getparam('gap-num', "");
	$gapCnt = getparam('gap-cnt', "");

	$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";

	$pid = getparam("pid", "0");
	
	$err = false;

	$res = mysqli_query($emperator, $query);
	if (!$res)
	{
		$to->regLine('DB Error');
		$err = true;
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			$to->regLine('DB Error');
			$err = true;
		} else {
			$pid = $prow['pers_id'];
		}
	}
	
	$query = "INSERT INTO surv ";
	$query .= "( pers, type, name, seq )" ;
	$query .= " VALUES ( " . $pid . ',' ;
	$query .= "7,'" . $gapName . "'," . $gapNum . ' )' ;

	$res = mysqli_query( $emperator, $query );
	$sid = 0;
	if (!$res)
	{
		$to->regLine('DB Error');
		$err = true;
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			$to->regLine('DB Error');
			$err = true;
		} else {
			$sid = $prow['surv_id'];
		}
	}

	for ($i=1; $i<=$gapCnt; ++$i)
	{
		$qq = 'q' . $i;
		$par = getparam($qq, 0);
		
		$query = "INSERT INTO data ";
		$query .= "( pers, type, value_a, value_b, surv )" ;
		$query .= " VALUES ( " . $pid . ',' ;
		$query .= "7," . $i . ',' . $par . ',' . $sid . ' )' ;

		if(!mysqli_query( $emperator, $query ))
		{
			$to->regLine('DB Error');
			$err = true;
		}
	}
	
	if (!$err)
		$to->regLine('All Ok.<br>');


?>

