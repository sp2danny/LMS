
<?php

function score($styr, $local, $common)
{
	echo <<<EOT
<style>
	table {
		margin:   7px;
	}
</style>
</head> <body>
EOT;

	global $emperator;


	$snum = getparam("seg", "1");

	$seg = 'segment-' . $snum;

	$qnum = 0;

	$pnr = getparam("pnr", "0");

	$eol = "\n";

	$totscore = 0;

	$curr = "";
	$bnum = 0;
	$max = 0;
	$maxseg = 0;

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
			if ($e[0] == 'max') {
				$maxseg = (int)$e[1];
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

	$ok = true;
	echo '<table><tr><td> <img width=50% height=50% src="../common/';
	if ($totscore == $qnum) {
		echo "corr";
	} else {
		echo "err";
		$ok = false;
	}
	echo '.png" > </td> <td> Po&auml;ng : ' . $totscore . ' / ' . $qnum . '</td></tr>' . $eol;
	echo '<td> <img width=50% height=50% src="../common/';
	$dintid = ((getparam('timestop')-getparam('timestart')) / 1000.0);
	$dintid = ((int)($dintid*10)) / 10.0;
	$maxt = getparam('timemax');
	if ($dintid < $maxt) {
		echo "corr";
	} else {
		echo "err";
		$ok = false;
	}
	echo '.png" > </td> <td> Tid : ' . $dintid . ' / ' . $maxt . '</td></tr>' . $eol;
	echo '</table>' . $eol;

	if ($ok) {
		$pnr = getparam('pnr');

		$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
		//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
		$res = mysqli_query($emperator, $query);

		if ($row = mysqli_fetch_array($res)) {

			$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $row['pers_id'] . ", 2, " . $bnum . ", " . $snum . ");";
			//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
			$res = mysqli_query($emperator, $query);
			if ($res) {
				echo '<br>registrerat i databasen<br><br>' . $eol;
				$query = 'UPDATE data SET value_a = value_a + 5 WHERE pers=' . $row['pers_id'] . ' AND type=4';
				//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
				$res = mysqli_query($emperator, $query);
				if ($res) {
					echo "all ok <br>\n";
				}
			}
		}

		if ($snum >= $maxseg)
			echo '<a href="' . '../common/personal.php?pnr=' . $pnr . '"> <button> next </button> </a>' . $eol;
		else
			echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> next </button> </a>' . $eol;
	} else {
		echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum) . '"> <button> igen </button> </a>' . $eol;
	}
}

?> 

</body>
</html>

