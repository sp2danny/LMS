
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php

include '../common/progress.php';
include '../common/process_cmd.php';

class tagNul
{
	public function startTag ($tag, $attr = '') {}
	public function stopTag  ($tag)             {}
	public function scTag    ($tag, $attr = '') {}
	public function regLine  ($line)            {}
}

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
	
	$to = new tagNul;
	
	$data = new Data;
	
	
	$curr = "";
	$bnum = 0;
	$maxseg = 0;
	$curr = '';

	while (true) {
		++$data->lineno;
		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		if ($buffer[0] == '#') continue;
		if ($buffer[0] == '!') {
			$p = strpos($buffer, ' ');
			if (!$p) continue;
			$cmd = substr($buffer, 1, $p-2);
			$rest = substr($buffer, $p+1);
			
			$s = substr($buffer, 1);
			$e = explode(' ', $s);
			if ($e[0] == 'batt') {
				$bnum = $e[1];
				continue;
			}
			if ($e[0] == 'max') {
				$maxseg = (int)$e[1];
				continue;
			}
		}

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr($buffer, 1, $len-2);
			continue;
		}
		
		if ($curr != $seg) continue;

		if ($buffer[0] == '!') {
			
			$sp = strpos($buffer, ' ');
			$cmd = '';
			$args = [];
			if (!$sp) {
				$cmd = substr($buffer, 1);
			} else {
				$cmd = substr($buffer, 1, $sp-1);
				$args = str_getcsv(substr($buffer, $sp+1), ';');
			}
				
			$w = process_cmd($to, $data, $cmd, $args);
			if (!($w===true))
				echo $w;
		}
	}

	//				if ($valnum > 0) {
	//					if ($s3[0] == '_') {
	//						if (getparam($qnum) == $valnum)
	//							$totscore += 1;
	//					}
	//				}
	
	$qnum = count($data->corr);
	for ($i=1; $i<=$qnum; ++$i)
	{
		if (getparam($i) == $data->corr[$i])
			++$totscore;
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
				$pos = strpos($buffer, ' ');
				$cmd = '';
				$rest = '';
				$expl = [];
				if ($pos) {
					$cmd = substr($buffer, 0, $pos);
					$rest = substr($buffer, $pos+1);
					$expl = str_getcsv($rest, ';');
				} else {
					$cmd = $buffer;
				}
				switch ($cmd) {
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
						
					case 'text':
						echo $rest . ' <br/> ' . $eol;
						break;
	
					case "prog":
						// TODO here
						$pro = progress($bnum, $maxseg);

						echo '<div class="container"> <div class="progress">' . $eol;
						echo '<div class="progress-bar" role="progressbar" aria-valuenow="' . $pro;
						echo '" aria-valuemin="0" aria-valuemax="100" style="width:' . $pro . '%">' . $eol;
						echo '<span class="sr-only">' . $pro . '% Complete</span>' . $eol;
						echo '</div></div></div>' . $eol;
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
							echo '<a href="' . '../common/personal.php?pnr=' . $pnr . '"> <button> ' . $expl[0] . ' </button> </a>' . $eol;
						else
							echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> ' . $expl[0] . ' </button> </a>' . $eol;
						break;
					case "again":
						echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum) . '"> <button> ' . $expl[0] . ' </button> </a>' . $eol;
						break;
					case "dbmsg":
						echo $dbtext . ' <br>' . $eol;
						break;
					case "image":
						if (count($expl) == 1)
							echo '<img src="../' . $expl[0] . '"> <br>' . $eol;
						else 
							echo '<img width="' . $expl[0] . '%" src="../' . $expl[1] . '"> <br>' . $eol;
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


?> 

</body>
</html>

