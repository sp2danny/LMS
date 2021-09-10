
<html>
<head> <title> Index </title> 
<link rel="stylesheet" href="../main.css">
<link rel="stylesheet" href="local.css">
</head><body>

<?php

include '../php/common.php';

$styr = fopen("styr.txt", "r") or die("Unable to open file!");

if ($styr) {

	$snum = getparam("seg", "1");

	$seg = 'segment-' . $snum;

	$pnr = getparam("pnr", "0");

	$qnum = 0;

	$eol = "\n";

	$corr = [];

	while (true) {

		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			continue;
		}

		if ($curr == $seg) {
			$s1 = substr( $buffer, 0, 2 );
			$s2 = substr( $buffer, 2 );

			if ($s1 == 't=') {
				// text
				echo $s2 . /*"<br>" .*/ $eol;
			} else if ($s1 == 'I=') {
				// echo '<iframe src="' . $s2 . '"> </iframe>' . $eol;
				$p = strpos($s2, ',');
				if ($p) {
					$s3 = substr($s2, 0, $p);
					$s2 = substr($s2, $p+1);
					echo '<iframe width=' . $s3 . '% height=' . $s3 . '%  src="' . $s2 . '"> </iframe>' . $eol;
				} else {
					echo '<iframe src="' . $s2 . '"> </iframe>' . $eol;
				}

			} else if ($s1 == 'a=') {
				echo '<audio controls>';
				echo '<source src="' . $s2 . '" type="audio/mp3"></audio>' . $eol;
				
			} else if ($s1 == 'h=') {
				echo '<h1>' . $s2 . '</h1>' . $eol;
			} else if ($s1 == 'l=') {
				echo '<hr color="' . $s2 . '" >' . $eol;
			} else if ($s1 == 'b=') {
				for ($i=0; $i<$s2; ++$i)
					echo '<br>';
				echo $eol;
			} else if ($s1 == 'i=') {
				$p = strpos($s2, ',');
				if ($p) {
					$s3 = substr($s2, 0, $p);
					$s2 = substr($s2, $p+1);
					echo '<img width=' . $s3 . '%  src="' . $s2 . '"> <br>' . $eol;
				} else {
					echo '<img src="' . $s2 . '"> <br>' . $eol;
				}
			} else if ($s1 == 'f=') {
				echo '<form action="' . $s2 . '" method="GET">' . $eol;
				echo '<input type="hidden" value="' . $snum . '" id="seg" name="seg" />' . $eol;
				echo '<input type="hidden" value="' . $pnr . '" id="pnr" name="pnr" />' . $eol;
				echo '<table>' . $eol;
			} else if ($s1 == 'e=') {
				// embed
				echo '<iframe width="1280" height="720" src="https://player.vimeo.com/video/';
				echo $s2;
				echo '"  frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>' . $eol;
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
						echo '<tr> <td colspan="2"> <h4>' . $s3 . ' </h4> </td> </tr> ' . $eol;
						echo '<tr> <td width="70px" > <img id="' . 'QI-' . $qnum . '" src="blank.png" /> </td> <td> ' . $eol;
						echo '<div class="form-group"><ol> ' . $eol;
					} else {
						echo ' <li> <input type="radio" id="' . 'QR-' . $qnum . '" name="' . $qnum . '" value="' . $valnum . '" />' . $s3 . '</li> ' . $eol;
					}
					if (!$p) break;
					$valnum++;
				}
				echo '</ol></div></td></tr>' . $eol;
				echo '<tr><td> &nbsp; </td></tr>' . $eol;
				//echo '<tr><td> &nbsp; </td></tr>' . $eol;
			} else if ($s1 == 'T=') {
				echo '<tr> <td colspan="2"> ' . $s2 . ' </td> </tr> ' . $eol;
			} else if ($s1 == 's=') {
				// submit
				echo '</table>' . $eol;
				echo '<script>' . $eol;
				echo 'function  doCorr() {' . $eol;
				for ($idx = 1; $idx <= $qnum; ++$idx) {
					echo '  corr1(' . $idx . ', ' . $corr[$idx] . ');' . $eol;
				}
				echo '}' . $eol;
				echo '</script>' . $eol;
				
				echo '<input type="submit" value="' . $s2 . '"/> <br>' . $eol;
				echo '</form>' . $eol;
				echo '<button onclick="doCorr()"> R&auml;tta </button> <br>' . $eol;
			} else if ($s1 == 'n=') {
				// next
				echo '<button onclick="location.href=' . "'" . 'index.php?seg=' . ($snum+1) . "'" . '" type="button"> ' . $s2 . '</button>' . $eol;
			}

		}

	}


} else {
	echo "<br> --- error --- <br>\r\n";
}

fclose($styr);






















?> 

</body>
</html>

