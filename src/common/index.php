
<?php

// include 'common.php';
// include 'tagOut.php';
// include 'connect.php';

function process_cmd($to, $corr, $cmd, $args)
{
	global $emperator;

	static $qnum = 0;

	$eol = "\n";

	switch ($cmd)
	{
		case 'text':
			$to->regLine($args[0]);
			break;
		case 'sound':
			$to->startTag('audio', 'controls');
			$to->regLine('<source src="' . $args[0] . '" type="audio/mp3">');
			$to->stopTag('audio');
			break;
		case 'header':
			$to->regLine('<h1>' . $args[0] . '</h1>');
			break;
		case 'line':
			$to->regLine('<hr color="' . $args[0] . '" />');
			break;
		case 'break';
			$ss = '';
			for ($i=0; $i<$args[0]; ++$i)
				$ss = $ss . '<br /> ';
			$to->regLine($ss);
			break;
		case 'image':
			if (count($args)>1) {
				$to->regLine('<img width=' . $args[0] . '%  src="' . $args[1] . '" /> <br />');
			} else {
				$to->regLine('<img src="' . $args[0] . '" /> <br />');				
			}
			break;
		case 'begin':
		
			//if (count($args) != 4) {
			//	echo ' *** WARNING *** <br />' . $eol;
			//	echo ' malformed "begin" command on line ' . $lineno . ', needs 4 parameters <br />' . $eol;
			//	echo ' *** WARNING *** <br />' . $eol;
			//	$args = explode(',', 'Starta, score.php, 130, lugn.mp3');
			//}
			$to->regLine('<button id="StartBtn" onclick="doShow()"> ' . trim($args[0]) . ' </button> <br />');
			$to->startTag('div', 'id="QueryBox" style="display:none;"');
			$to->regLine('<audio id="AudioBox" preload loop> <source src="' . trim($args[3]) . '" type="audio/mp3"></audio>');
			$to->startTag('form', 'action="' . trim($args[1]) . '" method="GET"');
			$to->scTag('input', 'type="hidden" value="' . $snum . '" id="seg" name="seg"');
			$to->scTag('input', 'type="hidden" value="' . $pnr . '" id="pnr" name="pnr"');
			$to->scTag('input', 'type="hidden" value="" id="TimeStart" name="timestart"');
			$to->scTag('input', 'type="hidden" value="" id="TimeStop" name="timestop"');
			$to->scTag('input', 'type="hidden" value="' . trim($args[2]) . '" id="TimeMax" name="timemax"');
			$to->scTag('input', 'type="hidden" value="0" id="Score" name="score"');
			$to->startTag('table');
			break;
		case 'video':
			$to->regLine('<iframe width="1280" height="720" src="https://player.vimeo.com/video/' . $args[0] . '"  frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>');
			break;
		case 'query':
			$qnum++;
			$valnum = 0;
			
			$to->regLine('<tr height="25px"> <td colspan="2"> <B>' . $args[0] . ' </B> </td> </tr>');
			$to->startTag('tr height="45px"');
			$to->regLine('<td width="45px" > <img id="' . 'QI-' . $qnum . '" src="../common/blank.png" /> </td>');
			$to->startTag('td');
			
			$n = count($args);
			for ($i=1; $i<$n; ++$i) {
				$ss = $args[$i];
				if ($ss && $ss[0] == '_') {
					$ss = substr($ss, 1);
					$corr[$qnum] = $valnum;
				}
				if ($valnum > 1) $to->regLine('<br />');
				$to->regLine('<input type="radio" id="' . 'QR-' . $qnum . '" name="' . $qnum . '" value="' . $valnum . '" />' . $ss . '');
				$valnum++;
			}
			$to->stopTag('td');
			$to->stopTag('tr');
			$to->regLine('<tr><td> &nbsp; </td></tr>');
			break;
			
		case 'submit':
			// submit
			// Rätta, Klar
			//if (count($args) != 2) {
			//	echo ' *** WARNING *** <br />' . $eol;
			//	echo ' malformed "s" command on line ' . $lineno . ', needs 2 parameters <br />' . $eol;
			//	echo ' *** WARNING *** <br />' . $eol;
			//	$args = explode(',', 'Rätta, Klar');
			//}

			$to->stopTag('table');
			$to->startTag('script');
			$to->regLine('function doCorr() {' );
			$to->regLine('  document.getElementById("TimeStop").value = (new Date()).getTime().toString();');
			$to->regLine('  var scr = 0;');
			for ($idx = 1; $idx <= $qnum; ++$idx) {
				$to->regLine('  if( corr1(' . $idx . ', ' . $corr[$idx] . ')) scr += 1;');
			}
			$to->regLine('  document.getElementById("SubmitBtn").style.display = "block";');
			$to->regLine('  document.getElementById("CorrBtn").style.display = "none";');
			$to->regLine('  document.getElementById("Score").value = scr.toString();');

			$to->regLine('}');
			$to->stopTag('script');

			$to->regLine('<input id="SubmitBtn" type="submit" value="' . $args[1] . '" style="display:none;" /> <br />');
			$to->stopTag('form');
			$to->regLine('<button  id="CorrBtn" onclick="doCorr()">' . $args[0] . '</button> <br />');
			$to->stopTag('div');
			
			break;
			
		case 'next':
			// next
			$to->startTag('button', 'onclick="location.href=' . "'" . 'index.php?seg=' . ($snum+1) . "'" . '" type="button"');
			$to->regLine($args[0]);
			$to->stopTag('button');
			break;
			
		default:
			echo ' *** WARNING *** <br />' . $eol;
			echo ' unrecognized command : "' . htmlspecialchars($cmd) . /*'" on line ' . $lineno .*/ ' <br />' . $eol;
			echo ' *** WARNING *** <br />' . $eol;
	}
	
}


function index($styr, $local, $common)
{
	global $emperator;

	$to = new tagOut;

	$snum = getparam("seg", "1");

	$seg = 'segment-' . $snum;

	$pnr = getparam("pnr", "0");

	$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
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

	$qnum = 0;

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

	$corr = [];
	$lineno = 0;
	$bnum = 0;

	while (true) {
		++$lineno;
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
				$args = explode(';', substr($buffer, $sp+1));
			}
				
			process_cmd($to, $corr, $cmd, $args);
		} else {
			to->regLine($buffer);
		}
	}
	$to->stopTag('div');
	$to->stopTag('body');
}

?> 
