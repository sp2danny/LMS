
<html>
<head> <title> Index </title> 
<link rel="stylesheet" href="../main.css">
<link rel="stylesheet" href="local.css">

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

echo '<meta http-equiv="refresh" content="time; URL=';

echo 'batt-' . getparam('batt') . "/index.php?seg=1&pnr=" . getparam('pnr');

echo '" />';


?> 

</head><body>
</body>
</html>

