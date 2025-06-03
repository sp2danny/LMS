
<html>
	<head>
		<title> person listning </title>
	</head>

	<body>

		<table>
			<tr>
				<th> Bat </th>
				<th> Del </th>
				<th> Score </th>
			</tr>
 
<?php

include 'connect.php';
include 'getparam.php';

$pid = getparam('pid');


$query = "SELECT * FROM data WHERE type=16 AND pers=$pid";
//$query = "INSERT INTO data (pers, type, value_a, value_b, surv) VALUES (" . $row['pers_id'] . ", 16, " . $bnum . ", " . $snum . ", " . $sc . ");";
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	echo "\t\t\t<tr>\n";
	echo "\t\t\t\t<td> " . $row['value_a'] . " </td>\n";
	echo "\t\t\t\t<td> " . $row['value_b'] . " </td>\n";
	echo "\t\t\t\t<td> " . $row['surv']    . " </td>\n";
	echo "\t\t\t</tr>\n";
}

?>

		</table>
	</body>
</html>

