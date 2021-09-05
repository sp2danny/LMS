
<html>
<head> <title> Index </title> 
<link rel="stylesheet" href="../main.css">
<link rel="stylesheet" href="local.css">
</head><body>

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

	$snum = getparam("seg", "1");

	$seg = 'segment-' . $snum;

	$qnum = 0;

	$pnr = getparam("pnr", "0");

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
			if ($s1 == 'q=') {
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
						//echo '<h3>' . $s3 . '</h3>' . $eol;
						//echo '<div class="form-group"><ol> ' . $eol;
					} else {
						//echo '<li> <input type="radio" name="' . $qnum . '" value="' . $valnum . '" />' . $s3 . '</li>' . $eol;
					}
					if (!$p) break;
					$valnum++;
				}
				//echo '</ol></div>' . $eol;
			}
		}

	}

	echo "<br>registrerat i databasen<br><br>";

	echo '<a href="' . 'index.php?pnr=' . $pnr . '&seg=' . ($snum+1) . '"> <button> next </button> </a>' . $eol;
}

fclose($styr);

?> 

</body>
</html>

