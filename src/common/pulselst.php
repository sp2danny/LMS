

<?php

class Pulse
{
	public string $name;
	public int $req;
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
		$p = new Pulse;
		$p->name = $a;
		$p->req = 0;
		$res[] = $p;
	}
	return $res;
}


?>


