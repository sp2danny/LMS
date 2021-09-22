
<?php

	$hostname_emperator = "danielstestdomain.se.mysql";
	$database_emperator = "danielstestdomain_selms"; 
	$username_emperator = "danielstestdomain_selms";
	$password_emperator = "aceirh18";

	$emperator = mysqli_connect(
		$hostname_emperator, 
		$username_emperator, 
		$password_emperator,
		$database_emperator
	);
	
	if(!$emperator)
	{
		echo " DB connect failed <br /> \n ";
	}

?>
