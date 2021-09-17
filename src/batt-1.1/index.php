
<?php

include '../php/head.php';
include '../php/common.php';
include '../php/tagOut.php';
include '../php/connect.php';

$styr = fopen("styr.txt", "r") or die("Unable to open file!");

if ($styr) {

	$to = new tagOut;

	$snum = getparam("seg", "1");

	$seg = 'segment-' . $snum;

	$pnr = getparam("pnr", "0");

	$name = getparam("name");

	$qnum = 0;

	$eol = "\n";

	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . $eol;

	echo '</head>' . $eol;
	$to->startTag('body');

	$to->startTag('div', 'class="sidenav"');

	$to->startTag('div', 'class="indent"');
	$to->regLine($name);
	$to->stopTag('div');
	$to->regLine('<br /> <br />');

	$to->startTag('div', 'class="indent" id="TimerDisplay"');
	$to->stopTag('div');
	$to->regLine('<br /> <br />');

	$to->stopTag('div');

	$to->startTag('div', 'class="main"');

	$corr = [];
	$lineno = 0;

	while (true) {
		++$lineno;
		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		if ($buffer[0] == '#') continue;

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			continue;
		}

		if ($curr == $seg) {
			$s1 = substr( $buffer, 0, 2 );
			$s2 = substr( $buffer, 2 );

			if ($s1 == 't=') {
				$to->regLine($s2);
			} else if ($s1 == 'I=') {
				$p = strpos($s2, ',');
				if ($p) {
					$s3 = substr($s2, 0, $p);
					$s2 = substr($s2, $p+1);
					$to->startTag('iframe', ' ' . $s3 . '  src="' . $s2 . '"');
					$to->stopTag('iframe');
				} else {
					$to->startTag('iframe', 'src="' . $s2 . '"');
					$to->stopTag('iframe');
				}
			} else if ($s1 == 'a=') {
				$to->startTag('audio', 'controls');
				$to->regLine('<source src="' . $s2 . '" type="audio/mp3">');
				$to->stopTag('audio');
			} else if ($s1 == 'h=') {
				$to->regLine('<h1>' . $s2 . '</h1>');
			} else if ($s1 == 'l=') {
				$to->regLine('<hr color="' . $s2 . '" />');
			} else if ($s1 == 'b=') {
				$ss = '';
				for ($i=0; $i<$s2; ++$i)
					$ss = $ss . '<br /> ';
				$to->regLine($ss);
			} else if ($s1 == 'i=') {
				$p = strpos($s2, ',');
				if ($p) {
					$s3 = substr($s2, 0, $p);
					$s2 = substr($s2, $p+1);
					$to->regLine('<img width=' . $s3 . '%  src="' . $s2 . '" /> <br />');
				} else {
					$to->regLine('<img src="' . $s2 . '" /> <br />');
				}
			} else if ($s1 == 'f=') {
				// Starta, score.php, 130, lugn.mp3
				$elems = explode(',', $s2);
				if (count($elems) != 4) {
					echo ' *** WARNING *** <br />' . $eol;
					echo ' malformed "f" command on line ' . $lineno . ', needs 4 parameters <br />' . $eol;
					echo ' *** WARNING *** <br />' . $eol;
					$elems = explode(',', 'Starta, score.php, 130, lugn.mp3');
				}
				$to->regLine('<button id="StartBtn" onclick="doShow()"> ' . trim($elems[0]) . ' </button> <br />');
				$to->startTag('div', 'id="QueryBox" style="display:none;"');
				$to->regLine('<audio id="AudioBox" preload loop> <source src="' . trim($elems[3]) . '" type="audio/mp3"></audio>');
				$to->startTag('form', 'action="' . trim($elems[1]) . '" method="GET"');
				$to->scTag('input', 'type="hidden" value="' . $snum . '" id="seg" name="seg"');
				$to->scTag('input', 'type="hidden" value="' . $pnr . '" id="pnr" name="pnr"');
				$to->scTag('input', 'type="hidden" value="" id="TimeStart" name="timestart"');
				$to->scTag('input', 'type="hidden" value="" id="TimeStop" name="timestop"');
				$to->scTag('input', 'type="hidden" value="' . trim($elems[2]) . '" id="TimeMax" name="timemax"');
				$to->scTag('input', 'type="hidden" value="0" id="Score" name="score"');
				$to->startTag('table');
			} else if ($s1 == 'e=') {
				// embed
				$to->regLine('<iframe width="1280" height="720" src="https://player.vimeo.com/video/' . $s2 . '"  frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>');
			} else if ($s1 == 'q=') {
				$qnum++;
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
					if ($s3 && $s3[0] == '_') {
						$s3 = substr($s3, 1);
						$corr[$qnum] = $valnum;
					}
					if ($valnum == 0) {
						$s3 = trim($s3);
						if (strlen($s3)>0)
							$to->regLine('<tr height="25px"> <td colspan="2"> <B>' . $s3 . ' </B> </td> </tr>');
						$to->startTag('tr height="45px"');
						$to->regLine('<td width="45px" > <img id="' . 'QI-' . $qnum . '" src="blank.png" /> </td>');
						$to->startTag('td');
					} else {
						if ($valnum > 1) $to->regLine('<br />');
						$to->regLine('<input type="radio" id="' . 'QR-' . $qnum . '" name="' . $qnum . '" value="' . $valnum . '" />' . $s3 . '');
					}
					if (!$p) break;
					$valnum++;
				}
				$to->stopTag('td');
				$to->stopTag('tr');
				$to->regLine('<tr><td> &nbsp; </td></tr>');
			} else if ($s1 == 'T=') {
				$to->regLine('<tr> <td colspan="2"> ' . $s2 . ' </td> </tr>');
			} else if ($s1 == 's=') {
				// submit
				// Rätta, Klar
				$elems = explode(',', $s2);
				if (count($elems) != 2) {
					echo ' *** WARNING *** <br />' . $eol;
					echo ' malformed "s" command on line ' . $lineno . ', needs 2 parameters <br />' . $eol;
					echo ' *** WARNING *** <br />' . $eol;
					$elems = explode(',', 'Rätta, Klar');
				}

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

				$to->regLine('<input id="SubmitBtn" type="submit" value="' . $elems[1] . '" style="display:none;" /> <br />');
				$to->stopTag('form');
				$to->regLine('<button  id="CorrBtn" onclick="doCorr()">' . $elems[0] . '</button> <br />');
				$to->stopTag('div');
			} else if ($s1 == 'n=') {
				// next
				$to->startTag('button', 'onclick="location.href=' . "'" . 'index.php?seg=' . ($snum+1) . "'" . '" type="button"');
				$to->regLine($s2);
				$to->stopTag('button');
			} else {
				echo ' *** WARNING *** <br />' . $eol;
				echo ' unrecognized command : "' . htmlspecialchars($buffer) . '" on line ' . $lineno . ' <br />' . $eol;
				echo ' *** WARNING *** <br />' . $eol;
			}
		}
	}

} else {
	echo "<br> --- error --- <br>\r\n";
}

fclose($styr);

$to->stopTag('div');
$to->stopTag('body');


?> 

</html>
