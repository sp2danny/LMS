
<?php

// <?
//  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
//  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
//  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
// >

include '../common/progress.php';
include '../common/process_cmd.php';
include '../common/cmdparse.php';

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
		$cmd = cmdparse($buffer);
		if ($cmd->is_empty) continue;
		if ($cmd->is_command) {
			if ($cmd->command == 'batt') {
				$bnum = (int)$cmd->rest;
				continue;
			}
			if ($cmd->command == 'max') {
				$maxseg = (int)$cmd->rest;
				continue;
			}
		}

		if ($cmd->is_segment) {
			$curr = $cmd->segment;
			continue;
		}
		
		if ($curr != $seg) continue;

		if ($cmd->is_command) {
			$w = process_cmd($to, $data, $cmd->command, $cmd->params);
			if (!($w===true))
				echo $w;
		}
	}

	$qnum = count($data->corr);
	for ($i=1; $i<=$qnum; ++$i)
	{
		if (getparam($i) == $data->corr[$i])
			++$totscore;
	}

	$ok = ($totscore == $qnum);

	$dintid = ((getparam('timestop')-getparam('timestart')) / 1000.0);
	$dintid = ((int)($dintid*10)) / 10.0;
	$maxt = getparam('timemax');
	if ($dintid > $maxt) {
		$ok = false;
	}

	$dbtext = "db-operation not performed";

	if ($ok) {
		$dbtext = "db-operation failed";
		$pnr = getparam('pnr');
		$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
		$dbtext = "db-operation >>" . $query . "<< failed.\n";
		$res = mysqli_query($emperator, $query);
		if ($row = mysqli_fetch_array($res)) {
			$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $row['pers_id'] . ", 2, " . $bnum . ", " . $snum . ");";
			$dbtext = "db-operation >>" . $query . "<< failed.\n";
			$res = mysqli_query($emperator, $query);
			if ($res) {
				$query = 'UPDATE data SET value_a = value_a + 5 WHERE pers=' . $row['pers_id'] . ' AND type=4';
				$dbtext = "db-operation >>" . $query . "<< failed.\n";
				$res = mysqli_query($emperator, $query);
				if ($res) {
					$dbtext = "all db-operations succeeded";
				}
			}
		}
	}

	$active = $ok ? "pass" : "fail";

	$mellan = fopen("../mellan.txt", "r");
	if ($mellan)
	{
		$curr = "none";
		while (true) {
			$buffer = fgets($mellan, 4096);
			if (!$buffer) break;
			$cmd = cmdparse($buffer);
			if ($cmd->is_empty) {
				if (($curr == $active) && !$cmd->is_comment)
					echo '<br>' . $eol;
				continue;
			}
			if ($cmd->is_segment) {
				$curr = $cmd->segment;
				continue;
			}
			if ($curr != $active)
				continue;
				
			if ($cmd->is_command) {
				switch ($cmd->command) {
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
						echo $cmd->rest . ' <br/> ' . $eol;
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
							echo '<a href="' . '../common/personal.php?pnr=' . $pnr . '"> <button> ' . $cmd->rest . ' </button> </a>' . $eol;
						else
							echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> ' . $cmd->rest . ' </button> </a>' . $eol;
						break;
					case "again":
						echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum) . '"> <button> ' . $cmd->rest . ' </button> </a>' . $eol;
						break;
					case "dbmsg":
						echo $dbtext . ' <br>' . $eol;
						break;
					case "image":
						if (count($cmd->params) == 1)
							echo '<img src="../' . $cmd->params[0] . '"> <br>' . $eol;
						else 
							echo '<img width="' . $cmd->params[0] . '%" src="../' . $cmd->params[1] . '"> <br>' . $eol;
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

