
<?php

function LoadIni($filename)
{
	$tf = fopen($filename, "r");
	if (!$tf) return false;
	
	$result = [];
	
	$segment = "";
	
	while (true)
	{
		$buffer = fgets($tf, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		if (strlen($buffer)==0) continue;
		if ($buffer[0] == '#') continue;
		if ($buffer[0] == '[') {
			$segment = substr($buffer,1,-1);
			continue;
		}
		$p = strpos($buffer, '=');
		if (!$p) continue;
		$key = trim(substr($buffer, 0, $p));
		$val = trim(substr($buffer, $p+1));
		$result[$segment][$key] = $val;
	}
	return $result;
}

function repl($str, $expr, $repl)
{
	return str_replace($expr, $repl, $str);
}

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

function get_styr($styr, $segment, $key, $variant = 0)
{
	$altkey = $key . "." . $variant;
	$res = '';
	if (array_key_exists($altkey, $styr[$segment]))
		$res = get_styr($styr, $segment, $altkey, $variant);
	else
		$res = get_styr($styr, $segment, $key, $variant);
	return $res;
}

