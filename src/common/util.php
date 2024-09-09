
<?php

function readini($ini)
{
	$res = [];
	$seg = '';

	while(true) {
		$buffer = fgets($ini, 4096);
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		
		if (str_starts_with($buffer, "#")) continue;

		if (str_starts_with($buffer, "[") && str_ends_with($buffer, "]"))
		{
			$seg = substr($buffer, 1, -1);
			$seg = trim($seg);
			continue;
		}
		
		$p = strpos($buffer, "=");
		if ($p === false) continue;
		
		$key = substr($buffer, 0, $p);
		$key = trim($key);
		$val = substr($buffer, $p+1);
		$val = trim($val);
		
		$res[$seg][$key] = $val;
	}

	return $res;
}

function addKV($lnk, $k, $v)
{
	if (strpos($lnk, '?')===false)
		return $lnk . '?' . $k . '=' . $v;
	else
		return $lnk . '&' . $k . '=' . $v;
}


?>
