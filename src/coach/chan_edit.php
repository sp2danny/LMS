

<!doctype html>

<html>

<head>
	<title> Edit Channel </title>

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
$nn = ""; $dd = "0"; $pp = "0"; $have = false;
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res))
{
	$nn = $row['value_c'];
	$dd = $row['value_b'];
	$pp = $row['value_a'];
	$have = true;
}


if ($have)
{
	echo <<<EOT

<body>

	<div class='start'>
	
		<h1> Redigera Kanal </h1>

		<form action="chan_edit_process.php">

			<input type='hidden' id='cid' name='cid' value='$cid' /> <br />
			<br />
			<label for='name'> Namn: </label> <br />
			<input type='text' id='name' name='name' value='$nn' /> <br />
			<br /> <br />
			<label for='platser'> Platser: </label> <br />
			<input type='number' id='platser' name='platser' value='$pp' /> <br />
			<br /> <br />
			<label for='dagar'> Dagar: </label> <br />
			<input type='number' id='dagar' name='dagar' value='$dd' /> <br />
			<br /> <br />
			<input type='submit' value='Spara' /> <br />
			<br /><br />

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

