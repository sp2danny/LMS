
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

		$qnum = 0;

		if ($curr == $seg) {
			$s1 = substr( $buffer, 0, 2 );
			$s2 = substr( $buffer, 2 );
			//echo $s1 . " , " . $s2 . "<br>";

			if ($s1 == 't=') {
				// text
				echo $s2 . "<br>";
			} else if ($s1 == 'f=') {
				echo '<form action="' . $s2 . '" method="post">';
			} else if ($s1 == 'e=') {
				// embed
			} else if ($s1 == 'q=') {
				$qnum++;
				$valnum = 0;
				$s3 = '';
				while (true) {
					$p = strpos($s2, ',');
					if ($p) {
						$s3 = substr($s2, 0, $p);
						$s2 = substr($s2, $p+1);
					} else {
						$s3 = $s2;
						$s2 = '';
					}
					if ($valnum == 0) {
						echo '<h3>' . $s3 . '</h3>';
						echo '<div class="form-group"><ol>';
					} else {
						echo '<li> <input type="radio" name="' . $qnum . '" value="' . $valnum . '" />' . $s3 . '</li>';
					}
					if (!$p) break;
					$valnum++;
				}
				echo '</ol></div>';

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

