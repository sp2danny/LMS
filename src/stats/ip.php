

<html>

	<head>
		<title>
			Stats
		</title>

		<style>
			table {
				border: 2px solid #000;
			}

			td {
				border: 1px solid #000;
				padding-top: 2px;
				padding-bottom: 2px;
				padding-right: 7px;
				padding-left: 7px;
			}

			th {
				background-color: #444;
				color: #eee;
				padding-top: 2px;
				padding-bottom: 2px;
				padding-right: 7px;
				padding-left: 7px;
			}

			body {
				margin-top: 50px;
				margin-bottom: 50px;
				margin-right: 150px;
				margin-left: 80px;
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

$n = 0;
foreach ($stat as $key => $value)
{
	echo $key . " - " . $value . " st <br> \n";
	$n += 1;
}

echo "<br><br> unique : " . $n . "<br> \n";

echo "<br> <hr>\n";


?>



	</div> </body>
</html>

