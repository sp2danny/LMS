
<!-- inlude common/score.php -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../common/main-v01.css">


<?php

// <?
//  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
//  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
//  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
// >

include_once '../common/progress.php';
include_once '../common/process_cmd.php';
include_once '../common/cmdparse.php';
include_once '../common/debug.php';
include_once '../common/tagOut.php';

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

	//<style> audio {display:none;} </style>

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

	$dbgt = "";

	$qnum = count($data->corr);
	for ($i=1; $i<=$qnum; ++$i)
	{
		$cr = getparam('q' . $i) == ($data->corr[$i]+1);
		$dbgt .= "q ". $i . " : " . ($cr?"ok":"fel") . " <br> \n";
		if ($cr)
			++$totscore;
	}

	if (getparam('debug')=='true')
		echo $dbgt;

	$ok = ($totscore == $qnum);

	$dintid = ((getparam('timestop')-getparam('timestart')) / 1000.0);
	$dintid = ((int)($dintid*10)) / 10.0;
	//$dintid = 1;
	$maxt = getparam('timemax');
	if ($dintid > $maxt) {
		$ok = false;
	}

	if (getparam('forceok')=='true')
		$ok = true;

	$dbtext = "db-operation was not performed";

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

	$dagens = array();
	$ord = @fopen("./common/ord.txt", "r");
	if (!$ord)
		$ord = @fopen("../common/ord.txt", "r");
	if ($ord)
	{
		while (true) {
			$buffer = fgets($ord, 4096);
			if (!$buffer) break;
			$buffer = trim($buffer);
			$len = strlen($buffer);
			if ($len == 0) continue;
			$cc = 0;
			for ($idx=0; $idx<$len; ++$idx)
				$cc = $cc ^ ord($buffer[$idx]);
			if ($len != 105 || $cc != 8)
				$dagens[] = $buffer;
		}
	}

	$active = $ok ? "pass" : "fail";

	$mellan = @fopen("mellan.txt", "r");
	if (!$mellan)
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
					
					case "motd":
					case "ord":
						$to->startTag('div');
						$n = $dagens ? count($dagens) : 0;
						if ($n > 0) {
							$i = rand(0, $n-1);
							echo '<br />';
							echo '<center>' . $dagens[$i] . '</center>';
						}
						$to->stopTag('div');
						break;

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
						$pro = round(progress($snum, $maxseg));
						echo '<div class="container"> <div class="progress">' . $eol;
						echo '<div class="progress-bar" role="progressbar" aria-valuenow="' . $pro;
						echo '" aria-valuemin="0" aria-valuemax="100" style="width:' . $pro . '%">' . $eol;
						echo '<span class="sr-only">' . $pro . '% Complete</span>' . $eol;
						echo ' &nbsp; ' . $pro . ' %' . $eol;
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
						if ($snum >= $maxseg) {
							//echo '<a href="' . '../common/personal.php?pnr=' . $pnr . '"> <button> ' . $cmd->rest . ' </button> </a>' . $eol;
							echo '<a href="' . '../common/rateit.php?pnr=' . $pnr . '&bnum=' . ($bnum+1) . '&snum=' . '1' . '&maxs=' . $maxseg .  '" > <button> ' . $cmd->rest . ' </button> </a>' . $eol;
						} else {
							echo '<a href="' . '../common/rateit.php?pnr=' . $pnr . '&bnum=' . $bnum . '&snum=' . ($snum+1) . '&maxs=' . $maxseg .  '" > <button> ' . $cmd->rest . ' </button> </a>' . $eol;
							//echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> ' . $cmd->rest . ' </button> </a>' . $eol;
						}
						break;
					case "again":
						echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum) . '"> <button> ' . $cmd->rest . ' </button> </a>' . $eol;
						break;
					case "dbmsg":
						echo $dbtext . ' <br>' . $eol;
						break;
					case "image":
						if (count($cmd->params) == 1)
							echo '<img src="' . $cmd->params[0] . '"> <br>' . $eol;
						else 
							echo '<img width="' . $cmd->params[0] . '%" src="' . trim($cmd->params[1]) . '"> <br>' . $eol;
						break;
					case 'break':
						$n = (int)$cmd->rest;
						for ($i=0; $i<$n; ++$i)
							echo '<br />';
						echo $eol;
						break;
					case 'sound':
						echo '<audio id="audiocontrol" autoplay="true" preload src="' . $cmd->params[0] . '" > </audio>' . $eol;
						echo '<script>' . $eol;
						echo '  setTimeout(function() {' . $eol;
						echo '    var au = document.getElementById("audiocontrol");' . $eol;
						echo '    au.play();' . $eol;
						echo '  }, 250)' . $eol;
						echo '</script>' . $eol;
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


