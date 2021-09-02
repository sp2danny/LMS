
<html>
<head> <title> Index </title> </head>
<body>

<?php



$styr = fopen("styr.txt", "r") or die("Unable to open file!");


if ($styr) {

	echo " --- start --- <br><br>";

	$seg = 'segment-' . htmlspecialchars($_GET["seg"]);

	while (true) {

		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			continue;
		}

		if ($curr == $seg) {
			$s1 = substr( $buffer, 0, 2 );
			$s2 = substr( $buffer, 2 );
			echo $s1 . " , " . $s2 . "<br>";
			if ($s1 == 't=') {
				// text
				echo $s2 . "<br>";
			if ($s1 == 'e=') {
				// embed
			} else if ($s1 == 'q=') {
				// query
			} else if ($s1 == 's=') {
				// submit
			} else if ($s1 == 'n=') {
				// next
			}

		}

	}

	echo "<br> --- stop --- <br>";

} else {
	echo "<br> --- error --- <br>";
}

fclose($styr);






















?> 

</body>
</html>

