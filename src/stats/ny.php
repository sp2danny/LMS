

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
$ymin = getparam('ymin', 2025);
if ($result) while ($row = mysqli_fetch_array($result))
{
	$dd = $row['date'];
	$arr = date_parse($dd);
	if ($arr['year'] < $ymin) continue;
	$nn = $row['name'];
	if (strpos($nn, "test") === false)
	{
		echo $dd;
		if ($nn != 'special')
			echo " " . $nn;

		$nn = $row['phone'];
		if ($nn != "special")
		{
			echo " - " . $nn;
		}

		echo " <br> \n";
		++$num;
	}
}

echo "<br><br> total : " . $num . " <br> \n";


echo "<br> <hr>\n";

$query = "SELECT * FROM data WHERE type=54";
$result = mysqli_query($emperator, $query);
$arr = [];
$num = 0;
if ($result) while ($row = mysqli_fetch_array($result))
{
	$dd = $row['date'];
	$arr = date_parse($dd);
	if ($arr['year'] < $ymin) continue;
	echo $dd;
	echo " - " . $row['value_a'];
	echo " <br> \n";
	++$num;
}

echo "<br> k&ouml;p : " . $num . " <br> \n";

$query = "SELECT NOW();";
$result = mysqli_query($emperator, $query);
if ($result) if ($row = mysqli_fetch_array($result))
{
	$dt = $row[0];
	echo " <br> completed " . $dt . " <br> \n";
}

?>



	</div> </body>
</html>

