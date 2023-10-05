
<html><head></head><body>


<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";
	
	
$title   = getparam('title');
$pdesc   = getparam('pdesc');
$price   = getparam('price');
$img     = getparam('img');
$unlocks = getparam('unlocks');


$query  = "INSERT INTO prod (name, pdesc, price, image, unlocks) VALUES";
$query .= " (" . "'" . $title   . "'";
$query .= ", " . "'" . $pdesc   . "'";
$query .= ", " . "'" . $price   . "'";
$query .= ", " . "'" . $img     . "'";
$query .= ", " . "'" . $unlocks . "'" . ")";

$res = mysqli_query( $emperator, $query );
	
if ($res)
	echo "all ok";
else
	echo "error";

?>

<br><br>
<a href="index.html">
<button> &nbsp; Ok &nbsp; </button>
</a>


</body>
</html>


