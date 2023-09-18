
<?php

	include "getparam.php";

	run();

	function run()
	{

		$hostname_emperator = "mind2excellence.se.mysql";
		$database_emperator = "mind2excellence_selms"; 
		$username_emperator = "mind2excellence_selms";
		$password_emperator = "Gra55bben";

		$emperator = mysqli_connect(
			$hostname_emperator, 
			$username_emperator, 
			$password_emperator,
			$database_emperator
		);

		$BaseDomain = "mind2excellence.se";
	
		if(!$emperator)
		{
			echo " DB connect failed <br /> \n ";
			return;
		}

		$pnr = getparam("pnr");

		$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
		$pid = 0;

		$res = mysqli_query( $emperator, $query );
		if ($res) {
			$row = mysqli_fetch_array($res);
			if ($row) {
				$pid = $row['pers_id'];
			}
		}

		if (!$pid)
			$pid = getparam("pid");

		if (!$pid) {
			echo " personen hittades inte <br /> \n ";
			return;
		}

		echo " <hr> <br> \n";

		$query = "SELECT * FROM data WHERE pers='" . $pid . "' AND type='51'";
		$acc = [];

		$res = mysqli_query( $emperator, $query );
		if ($res) while ($row = mysqli_fetch_array($res)) {
			$prod = $row['value_a'];
			$acc[] = $prod;
			echo " hittade " . $prod . " <br>\n";
		}

		echo " <hr> <br> \n";

		foreach ($acc as $val) {
			$query = "SELECT * FROM prod WHERE prod_id='" . $val . "'";
			$res = mysqli_query( $emperator, $query );
			if ($res) if ($row = mysqli_fetch_array($res)) {
				echo " > " . $row['name'] . " <br>\n";
			}
		}

		$res = mysqli_query( $emperator, $query );


	}



?>



