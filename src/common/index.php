
<!-- inlude common/index.php -->

<?php

include 'process_cmd.php';
include 'cmdparse.php';
include 'progress.php';
include 'main.js.php';

function index($styr, $local, $common)
{
	global $emperator;

	$to = new tagOut;
	
	$data = new Data;

	$data->snum = getparam("seg", "1");

	$seg = 'segment-' . $data->snum;

	$data->pnr = getparam("pnr", "0");

	$query = "SELECT * FROM pers WHERE pnr='" . $data->pnr . "'";

	$res = mysqli_query($emperator, $query);
	$mynt = 0;
	if (!$res)
	{
		$to->regLine('DB Error');
	} else {
		$prow = mysqli_fetch_array($res);
		
		if (!$prow) {
			$to->regLine('DB Error');
		} else {
			$query = 'SELECT * FROM data WHERE pers=' . $prow['pers_id'] . ' AND type=4';
			$res = mysqli_query($emperator, $query);
			if ($row = mysqli_fetch_array($res))
				$mynt = $row['value_a'];
		}
	}

	$name = getparam("name");

	$eol = "\n";

	$bnum = 1;
	$maxseg = 1;
	$curr = '';

	$cmdlst = [];

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

		$cmdlst[] = $cmd;
	}
	
	$data->bnum = $bnum;
	
	echo '<!-- ' . 'set bnum to ' . $data->bnum . ' -->';

	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . $eol;

	echo '  <style type="text/css">' . $eol;
	echo '    .btndiv {' . $eol;
	echo '      display: inline;' . $eol;
	echo '    }' . $eol;
	echo '  </style>' . $eol;

	echo '</head>' . $eol;
	$to->startTag('body');
	
	{
		$side = fopen("styrkant.txt", "r") or die("Unable to open file!");

		$to->startTag('div', 'class="sidenav"');
		$to->startTag('div', 'class="indent"');
		
		while (true) {
			$buffer = fgets($side, 4096); // or break;
			if (!$buffer) break;
			$cmd = cmdparse($buffer);
			if ($cmd->is_command) {
				switch ($cmd->command) {
					case 'text':
						$txt = $cmd->rest;
						$txt = str_replace('%name%', $prow['name'], $txt);
						$txt = str_replace('%coin%', $mynt, $txt);
						$txt = str_replace('%seg%', $data->snum, $txt);
						$txt = str_replace('%bat%', $bnum, $txt);
						$to->regLine($txt);
						break;
					case 'line':
						$to->regLine('<hr color="' . $cmd->rest . '" />');
						break;
					case 'image':
						if (count($cmd->params)>1) {
							$to->regLine('<img width=' . $cmd->params[0] . '%  src="' . $cmd->params[1] . '" /> <br />');
						} else {
							$to->regLine('<img src="' . $cmd->params[0] . '" /> <br />');				
						}
						break;
					case 'name':
						$to->regLine($prow['name']);
						break;
					case 'coin':
						$to->regLine($mynt . ' mynt.');
						break;
					case 'seg':
						$to->regLine($data->snum);
						break;
					case 'time':
						$to->startTag('div', 'class="indent" id="TimerDisplay"');
						$to->stopTag('div');
						break;
					case 'break':
						$n = (int)$cmd->rest;
						for ($i=0; $i<$n; ++$i)
							$to->regLine('<br />');
						break;
					case 'sound':
					
						break;
					case 'prog':
						$pro = (int)progress($data->snum, $maxseg);
						if ($pro<0) $pro = 0;
						if ($pro>100) $pro = 100;
						$to->regLine('<canvas id="myCanvas" width="200" height="120" ></canvas>');
						$to->startTag('script');
						$to->regLine('var pro = ' . $pro . ';');
						$to->regLine('var canvas = document.getElementById("myCanvas");');
						$to->regLine('var ctx = canvas.getContext("2d");');
						$to->regLine('ctx.fillStyle = "#F2F3F7";');
						$to->regLine('ctx.fillRect(0,0,200,200);');
						$to->regLine('ctx.strokeStyle = "#000";');
						$to->regLine('ctx.lineWidth = 12;');
						$to->regLine('ctx.beginPath();');
						$to->regLine('ctx.arc(100, 100, 75, 1 * Math.PI, 2 * Math.PI);');
						$to->regLine('ctx.stroke(); ');
						$to->regLine('ctx.strokeStyle = "#fff";');
						$to->regLine('ctx.lineWidth = 10;');
						$to->regLine('ctx.beginPath();');
						$to->regLine('ctx.arc(100, 100, 75, 1.01 * Math.PI, 1.99 * Math.PI);');
						$to->regLine('ctx.stroke();');
						$to->regLine('ctx.strokeStyle = "#7fff7f";');
						$to->regLine('ctx.lineWidth = 10;');
						$to->regLine('ctx.beginPath();');
						$to->regLine('ctx.arc(100, 100, 75, 1.01 * Math.PI, (1+(pro/100.0)) * Math.PI);');
						$to->regLine('ctx.stroke();');
						$to->regLine('ctx.fillStyle = "#7f7";');
						$to->regLine('ctx.lineWidth = 1;');
						$to->regLine('ctx.strokeStyle = "#000";');
						$to->regLine('ctx.font = "35px Arial";');
						$to->regLine('ctx.textAlign = "center"; ');
						$to->regLine('ctx.fillText( pro.toString() + " %", 100, 98); ');
						$to->regLine('ctx.strokeText( pro.toString() + " %", 100, 98); ');
						$to->stopTag('script');
						break;
				}
			}
		}

		$to->stopTag('div');
		$to->stopTag('div');

		fclose($side);
	}

	$to->startTag('div', 'class="main"');

	foreach ($cmdlst as $cmd) {

		if ($cmd->is_command) {
			$w = process_cmd($to, $data, $cmd->command, $cmd->params);
			if (!($w===true))
				echo $w;
		} else if ($cmd->is_text) {
			$to->regLine($cmd->text);
		}
	}
	$to->stopTag('div');
	$to->stopTag('body');
}

?> 
