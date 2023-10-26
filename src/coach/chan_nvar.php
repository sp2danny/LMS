

<!doctype html>

<html>

<head>
	<title> New Variant </title>

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
$nam = "--unknown--";
$query = "SELECT * FROM data WHERE type='70' AND data_id=$cid";
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res))
	$nam = $row['value_c'];

?>

<body>

	<div class='start'>
	
		<h1> Skapa Ny Variant </h1>
		<h2> kopplad till kanal <?php echo $nam ?> </h2>

		<form action="chan_nvar_process.php">

			<input type='hidden' id='cid' name='cid' value= <?php echo "'$cid'"; ?> />

			<br />
			<label for='var'> Variant: </label> <br />
			<input type='number' id='var' name='var' /> <br />
			<br /> <br />
			<label for='comm'> Kommentar: </label> <br />
			<input type='text' id='comm' name='comm' /> <br />
			<br /> <br />
			<input type='submit' value='Skapa' /> <br />
			<br /><br />

		</form>
	
	</div>
	
</body>
</html>
