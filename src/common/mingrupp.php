
<!DOCTYPE html>

<html>
<head>
	<title> Grupp </title>
</head>
<body>

<?php

include_once 'connect.php';
include_once 'getparam.php';


function discdisplay($pid)
{	
	global $emperator;

	$query1 = "SELECT * FROM data WHERE pers='$pid' AND type='6'";
	
	$have = false;
	$when = 0;

	$result1 = mysqli_query($emperator, $query1);
	if ($result1) while ($row1 = mysqli_fetch_array($result1)) {
		if (!$have) {
			$LR = $row1['value_a'];
			$UD = $row1['value_b'];
			$have = true;
			$when = $row1['date'];
		} else {
			$date = $row1['date'];
			if ($date > $when) {
				$LR = $row1['value_a'];
				$UD = $row1['value_b'];
				$when = $date;				
			}
		}
	}
	
	if ($have)
		return " " . $LR . ", " . $UD . " ";
	else
		return " -- inte gjort -- ";
}


echo "\t<br><br>\n";

echo "\t<table>\n";

echo "\t\t<tr>\n";

echo "\t\t\t<th> PNR </th> <th> Name </th> <th> disc </th>  \n";

echo "\t\t</tr>\n";

$pid = getparam('pid');
$query = "SELECT * FROM pers WHERE pers_id=$pid";
$res = mysqli_query($emperator, $query);
$prow = mysqli_fetch_array($res);

$grp = $prow['grupp'];

$query = "SELECT * FROM pers WHERE grupp='$grp'";;
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res)) {

	echo "\t\t<tr>\n";


	$pid = $row["pers_id"];
	$pnr = $row["pnr"];
	$nam = $row["name"];
	$dsc = discdisplay($pid);

	echo "\t\t\t<td> $pnr </td> <td> $nam </td> <td> $dsc </td> \n";

	echo "\t\t</tr>\n";

}

echo "\t</table>\n";

?>

</body>
</html>

