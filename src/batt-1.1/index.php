
<!DOCTYPE html>

<html>
<head> <title> Index </title> 
<link rel="stylesheet" href="../main-v001.css">
<link rel="stylesheet" href="local-v001.css">

<?php

include '../php/common.php';

include '../php/tagOut.php';

$styr = fopen("styr.txt", "r") or die("Unable to open file!");

if ($styr) {

	$to = new tagOut;

	$snum = getparam("seg", "1");

	$seg = 'segment-' . $snum;

	$pnr = getparam("pnr", "0");

	$qnum = 0;

	$eol = "\n";

	echo '</head>' . $eol;
	$to->startTag('body');

	$corr = [];

	while (true) {

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
				// text
				$to->regLine($s2);
			} else if ($s1 == 'I=') {
				// echo '<iframe src="' . $s2 . '"> </iframe>' . $eol;
				$p = strpos($s2, ',');
				if ($p) {
					$s3 = substr($s2, 0, $p);
					$s2 = substr($s2, $p+1);
					$to->startTag('iframe', 'width=' . $s3 . '% height=' . $s3 . '%  src="' . $s2 . '"');
					//echo '<iframe width=' . $s3 . '% height=' . $s3 . '%  src="' . $s2 . '"> </iframe>' . $eol;
					$to->stopTag('iframe');
				} else {
					//echo '<iframe src="' . $s2 . '"> </iframe>' . $eol;
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
				$to->startTag('form', 'action="' . $s2 . '" method="GET"');
				$to->scTag('input', 'type="hidden" value="' . $snum . '" id="seg" name="seg"');
				$to->scTag('input', 'type="hidden" value="' . $pnr . '" id="pnr" name="pnr"'); 
				$to->startTag('table');
				//echo '<form action="' . $s2 . '" method="GET">' . $eol;
				//echo '<input type="hidden" value="' . $snum . '" id="seg" name="seg" />' . $eol;
				//echo '<input type="hidden" value="' . $pnr . '" id="pnr" name="pnr" />' . $eol;
				//echo '<table>' . $eol;
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
						$to->regLine('<tr height="25px"> <td colspan="2"> <B>' . $s3 . ' </B> </td> </tr>');
						$to->startTag('tr height="45px"');
						$to->regLine('<td width="45px" > <img id="' . 'QI-' . $qnum . '" src="blank.png" /> </td>');
						$to->startTag('td');
						//$to->startTag('div', 'class="form-group"');
						//$to->startTag('ol');
						//echo '<tr> <td colspan="2"> <h4>' . $s3 . ' </h4> </td> </tr> ' . $eol;
						//echo '<tr> <td width="70px" > <img id="' . 'QI-' . $qnum . '" src="blank.png" /> </td> <td> ' . $eol;
						//echo '<div class="form-group"><ol> ' . $eol;
					} else {
						if ($valnum > 1) $to->regLine('<br />');
						$to->regLine('<input type="radio" id="' . 'QR-' . $qnum . '" name="' . $qnum . '" value="' . $valnum . '" />' . $s3 . '');
						//echo ' <li> <input type="radio" id="' . 'QR-' . $qnum . '" name="' . $qnum . '" value="' . $valnum . '" />' . $s3 . '</li> ' . $eol;
					}
					if (!$p) break;
					$valnum++;
				}
				//$to->stopTag('ol');
				//$to->stopTag('div');
				$to->stopTag('td');
				$to->stopTag('tr');
				//echo '</ol></div></td></tr>' . $eol;
				$to->regLine('<tr><td> &nbsp; </td></tr>');
				//echo '<tr><td> &nbsp; </td></tr>' . $eol;
			} else if ($s1 == 'T=') {
				$to->regLine('<tr> <td colspan="2"> ' . $s2 . ' </td> </tr>');
			} else if ($s1 == 's=') {
				// submit
				$to->stopTag('table');
				//echo '</table>' . $eol;
				$to->startTag('script');
				//echo '<script>' . $eol;
				$to->regLine('function  doCorr() {' );
				for ($idx = 1; $idx <= $qnum; ++$idx) {
					$to->regLine('  corr1(' . $idx . ', ' . $corr[$idx] . ');');
				}
				$to->regLine('}');
				$to->stopTag('script');
				//echo '</script>' . $eol;
				
				$to->regLine('<input type="submit" value="' . $s2 . '" /> <br />');
				$to->stopTag('form');
				//echo '</form>' . $eol;
				$to->regLine('<button onclick="doCorr()"> R&auml;tta </button> <br />');
			} else if ($s1 == 'n=') {
				// next
				$to->startTag('button', 'onclick="location.href=' . "'" . 'index.php?seg=' . ($snum+1) . "'" . '" type="button"');
				$to->regLine($s2);
				$to->stopTag('button');
			} else {
				echo ' *** WARNING *** <br />' . $eol;
				echo ' unrecognized command : ' . $buffer . '<br />' . $eol;
				echo ' *** WARNING *** <br />' . $eol;
			}

		}

	}


} else {
	echo "<br> --- error --- <br>\r\n";
}

fclose($styr);
















$to->stopTag('body');






?> 

</html>

