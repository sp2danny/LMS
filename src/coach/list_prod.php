
<html>

<head>
<title> Lista Produkter </title>

<style>

div {
  margin: 35px;
}

</style>

</head>

<body>

	<div>

	<h3> Lista Produkter </h3>

<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

$first = true;
$query = "SELECT * FROM prod";
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	if (!$first)
		echo "<hr>\n";

	echo "Produktnummer: " . $row['prod_id'] . " <br> \n";

	echo "Titel: " . $row['name'] . " <br> \n";

	echo "Beskrivning: " . $row['pdesc'] . " <br> \n";

	echo "Pris: " . $row['price'] . " <br> \n";
	
	echo "Bild: " . $row['image'] . " <br> \n";
	
	echo "Uppl&aring;sning: " . $row['unlocks'] . " <br> \n";
	
	$first = false;
}

?>

</body>
</html>



