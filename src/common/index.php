
<?php

// include 'common.php';
// include 'tagOut.php';
// include 'connect.php';

include 'process_cmd.php';

function index($styr, $local, $common)
{
	global $emperator;

	$to = new tagOut;
	
	$data = new Data;

	$data->snum = getparam("seg", "1");

	$seg = 'segment-' . $data->snum;

	$data->pnr = getparam("pnr", "0");

	$query = "SELECT * FROM pers WHERE pnr='" . $data->pnr . "'";
	//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
	$res = mysqli_query($emperator, $query);
	$prow = mysqli_fetch_array($res);

	$query = 'SELECT * FROM data WHERE pers=' . $prow['pers_id'] . ' AND type=4';
	//echo "trying : <br /> <code>\n" . $query . "\n</code><br />\n";
	$res = mysqli_query($emperator, $query);
	$mynt = 0;
	if ($row = mysqli_fetch_array($res))
		$mynt = $row['value_a'];

	$name = getparam("name");

	$eol = "\n";

	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . $eol;

	echo '</head>' . $eol;
	$to->startTag('body');

	$to->startTag('div', 'class="sidenav"');

	$to->startTag('div', 'class="indent"');
	$to->regLine($prow['name']);
	$to->regLine('<br /> <br />');
	$to->regLine($mynt . ' mynt.');
	$to->stopTag('div');
	$to->regLine('<br /> <br />');

	$to->startTag('div', 'class="indent" id="TimerDisplay"');
	$to->stopTag('div');
	$to->regLine('<br /> <br />');

	$to->stopTag('div');

	$to->startTag('div', 'class="main"');

	$bnum = 0;
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
			if ($cmd == 'batt') {
				$bnum = (int)$rest;
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
		} else {
			$to->regLine($buffer);
		}
	}
	$to->stopTag('div');
	$to->stopTag('body');
}

?> 
