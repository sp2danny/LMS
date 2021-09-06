
<html>
<head> <title> Index </title> 
<link rel="stylesheet" href="../main.css">
<link rel="stylesheet" href="local.css">
</head><body>

<?php

include '../php/common.php';

$styr = fopen("styr.txt", "r") or die("Unable to open file!");

if ($styr) {

	$snum = getparam("seg", "1");

	$seg = 'segment-' . $snum;

	$qnum = 0;

	$pnr = getparam("pnr", "0");

	$eol = "\n";

	$totscore = 0;

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
			if ($s1 == 'q=') {
				$qnum++;
				$valnum = 0;
				$s3 = '';
				while (true) {
					$p = strpos($s2, ',');
					if ($p) {
						$s3 = trim(substr($s2, 0, $p));
						$s2 = trim(substr($s2, $p+1));
					} else {
						$s3 = trim($s2);
						$s2 = '';
					}
					if ($valnum > 0) {
						if ($s3[0] == '_') {
							if (getparam($qnum) == $valnum)
								$totscore += 1;
						}
					}
					if (!$p) break;
					$valnum++;
				}
			}
		}

	}

	echo '<br> Score : ' . $totscore . '<br>' . $eol;
	echo '<br>registrerat i databasen<br><br>' . $eol;

	echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> next </button> </a>' . $eol;
}

fclose($styr);

?> 

</body>
</html>

