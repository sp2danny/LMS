
<?php

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
	}

?>
