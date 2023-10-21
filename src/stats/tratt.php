
<html>

	<head>
		<title>
			Tratten
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

		<hr />
		<h1> Leads </h1>


<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

$tag = getparam("tag", 0);
if ($tag==0) {
	$query = "SELECT * FROM data WHERE type='17'";
	$result = mysqli_query($emperator, $query);
	$arr = [];
	while ($row = mysqli_fetch_array($result)) {
		$lid = $row['value_a'];
		$var = $row['value_b'];
		if (array_key_exists($var, $arr))
			$arr[$var] += 1;
		else
			$arr[$var] = 1;
	}
	echo "<table> <tr> <th> variant </th> <th> antal </th> </tr> \n";
	foreach ($arr as $key => $val)
	{
		echo "<tr> <td> " . $key . " </td> ";
		echo " <td> " . $val . " </td> </tr> \n";
	}
	echo " </table> \n";
	
} else {
	
	$query = "SELECT * FROM data WHERE type='17' AND value_b='" . $tag . "'";
	$result = mysqli_query($emperator, $query);
	$num = 0;
	while ($row = mysqli_fetch_array($result)) {
		$num += 1;
	}
	echo "<table> <tr> <th> variant </th> <th> antal </th> </tr> \n";
	echo "<tr> <td> " . $tag . " </td> ";
	echo " <td> " . $num . " </td> </tr> \n";
	echo " </table> \n";
	
}

?>

		<br /> <hr />

	</div> </body>
</html>

