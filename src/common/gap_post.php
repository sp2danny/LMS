
<!-- inlude gap_post.php -->

<html>
<head>

<?php

	include_once('connect.php');
	include_once('common.php');

	$pnr  = getparam("pnr", "0");
	$bnum = getparam('bnum', 0);
	$snum = getparam('snum', 0);

	$pnr = getparam("pnr", "0");
	$gapName = getparam('gap-name', "");
	$gapNum = getparam('gap-num', "");
	$gapCnt = getparam('gap-cnt', "");

	$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";

	$pid = getparam("pid", "0");

	$err = false;

	$res = mysqli_query($emperator, $query);
	if (!$res) {
		$err = 'DB Error, query person --'.$query.'--';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			$err = 'DB Error, fetch person --'.$query.'--';
		} else {
			$pid = $prow['pers_id'];
		}
	}

	$query = "INSERT INTO surv ";
	$query .= "( pers, type, name, seq )" ;
	$query .= " VALUES ( " . $pid . ',' ;
	$query .= "7,'" . $gapName . "'," . $gapNum . ' )' ;

	$res = mysqli_query($emperator, $query);
	$sid = 0;
	if (!$res) {
		$err = 'DB Error, query insert surv --'.$query.'--';
	} else {
		$sid = $emperator->insert_id;
	}

	for ($i=1; $i<=$gapCnt; ++$i) {
		$qq = 'q' . $i;
		$par = getparam($qq, 0);

		$query = "INSERT INTO data ";
		$query .= "( pers, type, value_a, value_b, surv )" ;
		$query .= " VALUES ( " . $pid . ',' ;
		$query .= "7," . $i . ',' . $par . ',' . $sid . ' )' ;

		if(!mysqli_query( $emperator, $query )) {
			$err = 'DB Error, query insert data --'.$query.'--';
		}
	}

	$link = '../common/forward.php';
	$link .= '?pnr=' . $pnr ;
	$link .= '&bnum=' . $bnum ;
	$link .= '&snum=' . ($snum+1) ;
	$link .= '&ob='   . $bnum ;
	$link .= '&os='   . $snum ;

	if ($err === false) {

		echo '<meta http-equiv="refresh" content="0; URL=';
		echo $link;
		echo '" />';
		echo "</head><body>";
		echo "</body></html>";

	} else {

		echo "</head><body>";
		echo $err;
		echo "<br> <a href='" . $link . "'> Next </a> <br>";
		echo "</body></html>";

	}

?>

