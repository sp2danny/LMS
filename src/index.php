
<html>
<head> <title> Index </title> </head>
<body>

<?php



$styr = fopen("styr.txt", "r") or die("Unable to open file!");


if ($styr) {

	echo " --- start --- <br><br>";

	while (true) {

		$buffer = fgets($styr, 4096);

		if (!$buffer) break;

		echo $buffer;
		echo "<br><br>";

	}

	echo " --- stop --- <br><br>";

} else {
	echo " --- error --- <br><br>";
}

fclose($myfile);






















?> 

</body>
</html>

