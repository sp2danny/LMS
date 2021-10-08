

<?php

class Pulse
{
	public string $name;
	public string $path;
	public int $req;
}

function readpulse($path, $name)
{
	$styr = fopen($path . "/styr.txt", "r");
	if ($styr === false) return false;
	$p = new Pulse;
	$p->name = $name;
	$p->path = $path;

	$req = 0;
	$lineno = 0;
	while (true) {
		++$lineno;
		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		if ($buffer[0] == '#') continue;
		if ($buffer[0] == '!') {
			$s = substr($buffer, 1);
			$e = explode(' ', $s);
			if ($e[0] == 'req') {
				$req = (int)$e[1];
			}
			continue;
		}
	}
	$p->req = $req;
	fclose($styr);
	return $p;
}

function pulselst($path)
{
	$dircont = scandir($path);

	$res = array();

	foreach ($dircont as $key => $value) {
		if (strlen($value) < 5) continue;
		$a = substr($value, 0, 5);
		if ($a != 'puls-') continue;
		$a = substr($value, 5);
		$res[] = readpulse($path . "/" . $value, $a);
	}
	return $res;
}


?>


