
<!-- inlude post_disc.php -->

<html>
<head>

<?php

	include_once 'connect.php';
	include_once 'common.php';
	include_once 'debug.php';

	$pnr = getparam("pnr", "0");
	$bnum = getparam('bnum', 0);
	$snum = getparam('snum', 0);

	$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";

	$res = mysqli_query($emperator, $query);
	$pid = 0;
	$name = '';

	if (!$res)
	{
		echo 'DB Error';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			echo 'DB Error';
		} else {
			$pid = $prow['pers_id'];
			$name = $prow['name'];
		}
	}

	$LR = 0;
	$UD = 0;

	for ($i=0; $i<20; ++$i) {
		$LR += $_GET[ 'LR' . $i ];
		$UD += $_GET[ 'UD' . $i ];
	}
	
	if (($UD != 0) || ($LR != 0)) {

		$query = "INSERT INTO data ";
		$query .= "( pers, type, value_a, value_b )" ;
		$query .= " VALUES ( " . $pid . ',' ;
		$query .= "6," . $UD . ',' . $LR . ' )' ;

		if(!mysqli_query( $emperator, $query ))
		{
			echo "<br>error<br>";
			die('Error: ' . mysqli_error($emperator));
		}
		
		$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $pid . ", 2, " . $bnum . ", " . $snum . ");";
		//$dbtext = "db-operation >>" . $query . "<< failed.\n";
		$res = mysqli_query($emperator, $query);

	}
	
	$link = '';
	
	$returnto = getparam('returnto', false);
	
	if ($returnto) {
		$link = '../common/' . $returnto . '.php';
		$link .= '?pnr=' . $pnr ;
	} else {
		$link = '../common/forward.php';
		$link .= '?pnr=' . $pnr ;
		$link .= '&bnum=' . $bnum ;
		$link .= '&snum=' . ($snum+1) ;
	}
	
	debug_log('post_disc ' . $UD . ',' . $LR . ' return ' . $link);

	echo '<meta http-equiv="refresh" content="0; URL=';
	echo $link;
	echo '" />';

?> 

</head>
<body></body>
</html>


