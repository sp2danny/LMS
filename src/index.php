
<html>
<head> <title> Index </title> </head>
<body>

<?php


function getparam($key, $def = "")
{
	$ok = false;
	$res = $def;

	try {
		if (array_key_exists("$key", $_GET)) {
			$res = $_GET[$key];
			$ok = true;
		}
	} catch(Exception $e) {
	}

	if (!$ok) try {
		if (array_key_exists($key, $_POST)) {
			$res = $_POST[$key];
			$ok = true;
		}
	} catch(Exception $e) {
	}

	if ($ok)
	{
		return $res;
	} else {
		return $def;
	}
}


$styr = fopen("styr.txt", "r") or die("Unable to open file!");


if ($styr) {

	$seg = 'segment-' . htmlspecialchars(getparam("seg", "1"));

	$qnum = 0;

	$eol = "\n";

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
			//echo $s1 . " , " . $s2 . "<br>";

			if ($s1 == 't=') {
				// text
				echo $s2 . "<br>" . $eol;
			} else if ($s1 == 'f=') {
				echo '<form action="' . $s2 . '" method="POST">' . $eol;
				echo '<input type="hidden" value="' . $seg . '" id="seg" name="seg">' . $eol;
				echo '<input type="hidden" value="' . $s2 . '" id="batteri" name="batteri">' . $eol;
				echo '<input type="hidden" value="7211064634" id="person" name="person">' . $eol;
			} else if ($s1 == 'e=') {
				// embed
				//echo '<iframe src="https://player.vimeo.com/video/';
				//echo $s2;
				//echo '" width="640" height="360" frameborder="1" allow="autoplay; fullscreen" allowfullscreen></iframe>\n';


				echo '<div style="padding:75% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/597083498?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&amp;h=3c84b2f529"';
				echo ' frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:75%;height:75%;" title="out_00.mp4"></iframe></div>';
				echo '<script src="https://player.vimeo.com/api/player.js"></script>' . $eol;


			} else if ($s1 == 'q=') {
				$qnum++;
				$valnum = 0;
				$s3 = '';
				while (true) {
					$p = strpos($s2, ',');
					if ($p) {
						$s3 = substr($s2, 0, $p);
						$s2 = substr($s2, $p+1);
					} else {
						$s3 = $s2;
						$s2 = '';
					}
					if ($valnum == 0) {
						echo '<h3>' . $s3 . '</h3>' . $eol;
						echo '<div class="form-group"><ol> ' . $eol;
					} else {
						echo '<li> <input type="radio" name="' . $qnum . '" value="' . $valnum . '" />' . $s3 . '</li>' . $eol;
					}
					if (!$p) break;
					$valnum++;
				}
				echo '</ol></div>' . $eol;

			} else if ($s1 == 's=') {
				// submit
				echo '<input type="submit" value="' . $s2 . '">' . $eol;
			} else if ($s1 == 'n=') {
				// next
			}

		}

	}

	//echo "<br> --- stop --- <br>";

} else {
	echo "<br> --- error --- <br>\r\n";
}

fclose($styr);






















?> 

</body>
</html>

