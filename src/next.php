
<html>
<head>

<?php

include 'php/common.php';
include 'php/connect.php';

function convert($str)
{
	$str = str_replace('å', '&aring' , $str);
	$str = str_replace('ä', '&auml' , $str);
	$str = str_replace('ö', '&ouml' , $str);
	$str = str_replace('Å', '&Aring' , $str);
	$str = str_replace('Ä', '&Auml' , $str);
	$str = str_replace('Ö', '&Ouml' , $str);
	return $str;
}

// pnr=721106&batt=1.1

$pnr = getparam('pnr');
$batt = getparam('batt');

$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
$res = mysqli_query($emperator, $query);
if ($row = mysqli_fetch_array($res)) {

	echo '<meta http-equiv="refresh" content="0; URL=';
	echo 'batt-' . $batt . "/index.php?seg=1&pnr=" . $pnr . "&pid=" . $row['pers_id'] . "&name=" . $row['name'];
	echo '" />';
	echo "</head><body>";

} else {

	echo "</head><body>";
	echo convert("Hittade ingen sådan person");
	echo "<br /><br /> <a href='login.php'> <button> ";
	echo convert("Försök igen");
	echo "</button> </a>";
	echo "<br /><br /> <a href='nypers.php'> <button> ";
	echo convert("Skapa nytt konto");
	echo "</button> </a>";

}

?> 

</body>
</html>

