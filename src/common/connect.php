
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

function data_first($que)
{
	global $emperator;
	$res = mysqli_query($emperator, $que);
	if (!$res) return false;
	$saved = false;
	while($row = mysqli_fetch_array($res))
	{
		if ($saved === false) {
			$saved = $row;
		} else {
			if (strtotime($row['date']) < strtotime($saved['date']))
				$saved = $row;
		}
	}
	return $saved;	
}

function data_last($que)
{
	global $emperator;
	$res = mysqli_query($emperator, $que);
	if (!$res) return false;
	$saved = false;
	while($row = mysqli_fetch_array($res))
	{
		if ($saved === false) {
			$saved = $row;
		} else {
			if (strtotime($row['date']) > strtotime($saved['date']))
				$saved = $row;
		}
	}
	return $saved;	
}


?>
