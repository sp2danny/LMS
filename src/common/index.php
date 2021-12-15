
<?php

include 'process_cmd.php';
include 'cmdparse.php';

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
	$prow = mysqli_fetch_array($res);

	$query = 'SELECT * FROM data WHERE pers=' . $prow['pers_id'] . ' AND type=4';

	$res = mysqli_query($emperator, $query);
	$mynt = 0;
	if ($row = mysqli_fetch_array($res))
		$mynt = $row['value_a'];

	$name = getparam("name");

	$eol = "\n";

	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . $eol;

	echo '</head>' . $eol;
	$to->startTag('body');
	
	{
		$side = fopen("kant.txt", "r") or die("Unable to open file!");

		$to->startTag('div', 'class="sidenav"');
		$to->startTag('div', 'class="indent"');
		
		while (true) {
			$buffer = fgets($side, 4096); // or break;
			if (!$buffer) break;
			$cmd = cmdparse($buffer);
			if ($cmd->is_command) {
				switch ($cmd->command) {
					case 'text':
						$to->regLine($cmd->rest);
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
						$to->regLine('segment ' . $data->snum);
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
				}
			}
		}

		$to->stopTag('div');
		$to->stopTag('div');

		fclose($side);
	}

	$to->startTag('div', 'class="main"');

	$bnum = 0;
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
		} else {
			$to->regLine($buffer);
		}
	}
	$to->stopTag('div');
	$to->stopTag('body');
}

?> 
