
<?php

include '../php/head.php';
include '../php/common.php';
include '../php/connect.php';

echo <<<EOT

<style>
	table {
	  margin:   7px;
	}
</style>
EOT;

echo '</head> <body>' . "\n";

$styr = fopen("styr.txt", "r") or die("Unable to open file!");

if ($styr) {

	$snum = getparam("seg", "1");

	$seg = 'segment-' . $snum;

	$qnum = 0;

	$pnr = getparam("pnr", "0");

	$eol = "\n";

	$totscore = 0;

	$curr = "";
	$bnum = 0;
	$max = 0;

	while (true) {

		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		if ($buffer[0] == '#') continue;
		if ($buffer[0] == '!') {
			$s = substr($buffer, 1);
			$e = explode(' ', $s);
			if ($e[0] == 'batt') {
				$bnum = $e[1];
			}
			continue;
		}

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			continue;
		}

		if ($curr == $seg) {
			$s1 = substr( $buffer, 0, 2 );
			$s2 = substr( $buffer, 2 );
			if ($s1 == 'q=') {
				$qnum++;
				$max++;
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

	$pnr = getparam('pnr');

	$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
	echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
	$res = mysqli_query($emperator, $query);

	if ($row = mysqli_fetch_array($res)) {

		$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $row['pers_id'] . ", 2, " . $bnum . ", " . $snum . ");";
		echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
		$res = mysqli_query($emperator, $query);
		if ($res) {
			echo '<br>registrerat i databasen<br><br>' . $eol;
		}
	}





	echo '<br> Score 1 : ' . $totscore . '<br>' . $eol;
	echo '<br> Score 2 : ' . getparam('score') . '<br>' . $eol;
	echo '<br> Det tog ' . ((getparam('timestop')-getparam('timestart')) / 1000.0) . ' s att genomf&ouml;ra <br>' . $eol;


	echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> next </button> </a>' . $eol;
}

fclose($styr);

?> 

</body>
</html>

