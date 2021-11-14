
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
	//echo '<table><tr><td> <img width=50% height=50% src="../common/';
	if ($totscore == $qnum) {
		//echo "corr";
	} else {
		//echo "err";
		$ok = false;
	}
	//echo '.png" > </td> <td> Po&auml;ng : ' . $totscore . ' / ' . $qnum . '</td></tr>' . $eol;
	//echo '<td> <img width=50% height=50% src="../common/';
	$dintid = ((getparam('timestop')-getparam('timestart')) / 1000.0);
	$dintid = ((int)($dintid*10)) / 10.0;
	$maxt = getparam('timemax');
	if ($dintid < $maxt) {
		//echo "corr";
	} else {
		//echo "err";
		$ok = false;
	}
	//echo '.png" > </td> <td> Tid : ' . $dintid . ' / ' . $maxt . '</td></tr>' . $eol;
	//echo '</table>' . $eol;

	$dbtext = "db-operation not performed";

	if ($ok) {
		
		$dbtext = "db-operation failed";
		
		$pnr = getparam('pnr');

		$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
		//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
		$res = mysqli_query($emperator, $query);

		if ($row = mysqli_fetch_array($res)) {

			$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $row['pers_id'] . ", 2, " . $bnum . ", " . $snum . ");";
			//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
			$res = mysqli_query($emperator, $query);
			if ($res) {
				//echo '<br>registrerat i databasen<br><br>' . $eol;
				$query = 'UPDATE data SET value_a = value_a + 5 WHERE pers=' . $row['pers_id'] . ' AND type=4';
				//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
				$res = mysqli_query($emperator, $query);
				if ($res) {
					//echo "all ok <br>\n";
					$dbtext = "db-operation succeeded";
				}
			}
		}

		//if ($snum >= $maxseg)
		//	echo '<a href="' . '../common/personal.php?pnr=' . $pnr . '"> <button> next </button> </a>' . $eol;
		//else
		//	echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> next </button> </a>' . $eol;
	} else {
		//echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum) . '"> <button> igen </button> </a>' . $eol;
	}

	//echo '<hr>' . $eol;

	$active = $ok ? "pass" : "fail";

	$mellan = fopen("../mellan.txt", "r");
	if ($mellan)
	{
		$curr = "none";
		while (true) {
			$buffer = fgets($mellan, 4096);
			if (!$buffer) break;
			$buffer = trim($buffer);
			$len = strlen($buffer);
			if ($len == 0) {
				if ($curr == $active)
					echo '<br>' . $eol;
				continue;
			}
			if ($buffer[0] == '#') {
				continue;
			}
			if ($buffer[0] == '[') {
				$curr = substr($buffer, 1, $len-2);
				continue;
			}
			if ($curr != $active)
				continue;
				
			if ($buffer[0] == '!') {
				$buffer = substr($buffer, 1);
				$expl = str_getcsv($buffer, ' ');
				switch ($expl[0]) {
					case "logo":
						echo '<img width=90% src="logo.png"> <br>' . $eol;
						break;
					case "score":
						echo '<table><tr><td> <img width=50% height=50% src="../common/';
						if ($totscore == $qnum) {
							echo "corr";
						} else {
							echo "err";
						}
						echo '.png" > </td> <td> Po&auml;ng : ' . $totscore . ' / ' . $qnum . '</td></tr>' . $eol;
						echo '</table>' . $eol;
						break;
					case "time":
						echo '<table><tr><td> <img width=50% height=50% src="../common/';
						if ($dintid < $maxt) {
							echo "corr";
						} else {
							echo "err";
						}
						echo '.png" > </td> <td> Tid : ' . $dintid . ' / ' . $maxt . '</td></tr>' . $eol;
						echo '</table>' . $eol;
						break;
					case "next":
						if ($snum >= $maxseg)
							echo '<a href="' . '../common/personal.php?pnr=' . $pnr . '"> <button> ' . $expl[1] . ' </button> </a>' . $eol;
						else
							echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> ' . $expl[1] . ' </button> </a>' . $eol;
						break;
					case "again":
						echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum) . '"> <button> ' . $expl[1] . ' </button> </a>' . $eol;
						break;
					case "dbmsg":
						echo $dbtext . ' <br>' . $eol;
						break;
					case "image":
						echo '<img src="../' . $expl[1] . '"> <br>' . $eol;
						break;
				}
				
			} else {
				echo $buffer . $eol;
				echo '<br>' . $eol;
			}
		}
	}
	fclose($mellan);

}

/*
[pass]
!score
!time
!next "Nästa"
[fail]
!score
!time
!again "Igen"
*/


?> 

</body>
</html>

