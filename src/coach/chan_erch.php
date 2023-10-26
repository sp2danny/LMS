

<!doctype html>

<html>

<head>
	<title> Erase Channel </title>

	<style>

		div.start {
		  margin: 35px;
		}

	</style>

</head>

<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

$cid = getparam('cid');

$query  = "SELECT * FROM data WHERE type='70' and data_id='$cid'";
$nn = "";
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res))
{
	$nn = $row['value_c'];
	$have = true;
}


if ($have)
{
	echo <<<EOT

<body>

	<div class='start'>
	
		<h1> Ta Bort Kanal $nn </h1>

		<form action="chan_erch_process.php">

			<input type='hidden' id='cid' name='cid' value='$cid' /> <br />
			Kanalen kommer att tas bort. Är du säker? <br> <br>
			<input type='submit' value='Ta Bort' /> <br /> <br>

		</form>
		
		<a href="channel.php"> <button> Avbryt </button> </a>

	</div>
	
</body>

EOT;

} else {

	echo <<<EOT
	
<body>

	<div class='start'>
	
		Något gick fel

		<a href="channel.php"> <button> Tillbaka </button> </a>

	</div>
	
</body>

EOT;

}

?>

</html>

