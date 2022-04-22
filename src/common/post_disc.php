
<!-- inlude post_disc.php -->

<?php

	include('connect.php');
	include('common.php');

	$pnr = getparam("pnr", "0");

	$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";

	$res = mysqli_query($emperator, $query);
	$pid = 0;
	if (!$res)
	{
		echo 'DB Error';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			echo 'DB Error';
		} else {
			$pid = $prow['pers_id'];
		}
	}

	$LR = 0;
	$UD = 0;

	for ($i=0; $i<20; ++$i) {
		$LR += $_GET[ 'LR' . $i ];
		$UD += $_GET[ 'UD' . $i ];
	}

	$query = "INSERT INTO data ";
	$query .= "( pers, type, value_a, value_b )" ;
	$query .= " VALUES ( " . $pid . ',' ;
	$query .= "6," . $UD . ',' . $LR . ' )' ;

	if(!mysqli_query( $emperator, $query ))
	{
		echo "<br>error<br>";
		die('Error: ' . mysqli_error($emperator));
	}

	mysqli_close($emperator);

	echo getparam('next') . "<br>\n";

?>


