

<!doctype html>

<html>

	<head>
		<title>
			Stats IP
		</title>

		<style>
			body {
				margin-top:     50px;
				margin-bottom:  50px;
				margin-right:  150px;
				margin-left:    80px;
			}

			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
			
				padding-top:     6px;
				padding-left:   20px;
				padding-right:  20px;
				padding-bottom:  6px;
			}

			th {
				text-align: left;
				background-color: #fff;
			}

			tbody, tr:nth-child(odd) {
				background-color: #fee;
			}
			tbody, tr:nth-child(even) {
				background-color: #eef;
			}

		</style>

	</head>

	<body> <div>

		<br />
		<h1> Utskick Lista </h1>
		<br />

<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

$query = "SELECT * FROM lead";
$result = mysqli_query($emperator, $query);
$arr = [];
$num = 0;
$stat = [];
$ymin = getparam('ymin', 2025);
if ($result) while ($row = mysqli_fetch_array($result))
{
	$dd = $row['date'];
	$arr = date_parse($dd);
	if ($arr['year'] < $ymin) continue;
	$nn = $row['name'];
	if (strpos($nn, "test") !== false) continue;

	$ip = $row['phone'];
	if ($ip == "special") $ip = 'none';
	if (array_key_exists($ip, $stat))
		$stat[$ip] += 1;
	else
		$stat[$ip] = 1;
}

echo "\t\t<table> <tr>\n";
echo "\t\t\t<th> IP </th><th> Antal </th> \n";
echo "\t\t</tr>";


$n = 0;
foreach ($stat as $key => $value)
{
	echo "<tr>\n";
	echo "\t\t\t <td> " . $key . " </td> <td> " . $value . " </td>\n";
	echo "\t\t</tr>";
	$n += 1;
}

echo "\n\t\t</table>\n";

echo "\t\t<br><br> unique : " . $n . "<br> \n";

echo "\t\t<br> <hr>\n";

?>

	</div> </body>
</html>

