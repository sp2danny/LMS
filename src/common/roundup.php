
<!-- inlude roundup.php -->

<?php

function segments($battname)
{
	$styr = fopen('../batt-' . $battname . "/styr.txt", "r");
	if ($styr === false) return false;

	$res = [];
	$curr = '';
	$lineno = 0;
	$maxs = 999;
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
			if ($e[0] == 'max') {
				$maxs = (int)$e[1];
				continue;
			}
		}

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			$res[$curr] = [];
			continue;
		}

		if ($curr != '')
			$res[$curr][] = $buffer;
	}
	fclose($styr);
	return $res;
}

class Line {
	public bool $isLink = false;
	public string $link;
	public bool $hasDone;
	public bool $always = false;
	public int $segment;
	public string $name;
	public string $segIdx;
}

class Block {
	public $lines = [];
	public bool $allDone = false;
	public bool $someDone = false;
	public int $battNum;
	public string $name;
	public int $atnum = 0;
}

function mklink($batt, $seg, $pnr, $pid, $name)
{
	return '../batt-' . $batt . '/index.php?seg=' . $seg . '&pnr=' . $pnr . '&pid=' . $pid . '&name='  . $name ;
}

function roundup($pnr, $pid, $name)
{
	global $emperator;
	
	$dircont = scandir("..");

	$batts = array();

	foreach ($dircont as $key => $value) {
		if (strlen($value) < 5) continue;
		$a = substr($value, 0, 5);
		if ($a != 'batt-') continue;
		$a = substr($value, 5);
		$batts[] = $a;
	}

	$allsofar = true;

	$alldata = [];
	
	$runnum = 0;

	foreach ($batts as $key => $value) {
		++$runnum;

		$alldata[$runnum] = new Block;
		$alldata[$runnum]->battNum = $runnum;
		$alldata[$runnum]->name = $value;

		$segs = segments($value);
		$done = [];
		for ($i=1; $i<=count($segs); ++$i) {
			$done[$i] = false;
		}

		$query = 'SELECT * FROM data WHERE pers=' . $pid . ' AND type=2 AND value_a=' . ($key+1) ;
		$res = mysqli_query($emperator, $query);
		while ($row = mysqli_fetch_array($res)) {
			$done[$row['value_b']] = true;
		}
		$i = 0;

		// $wantlink = true;

		foreach ($segs as $segIdx => $segVal) {
			//for ($i=1; $i<=count($segs); ++$i) {
			++$i;

			$alldata[$runnum]->lines[$i] = new Line;
			$alldata[$runnum]->lines[$i]->segment = $i;
			$alldata[$runnum]->lines[$i]->name = 'Del ' . $i;
			$alldata[$runnum]->lines[$i]->segIdx = $segIdx;

			$thisok = false;
			if (array_key_exists($i, $done) && $done[$i])
				$thisok = true;

			$alldata[$runnum]->lines[$i]->hasDone = $thisok;
			
			foreach ($segVal as $lnum => $daline) {
				if ($daline == '!always')
					$alldata[$runnum]->lines[$i]->always = true;
			}

			$wantlink = false;

			if (!$thisok && $allsofar) {
				$allsofar = false;
				$wantlink = true;
				$alldata[$runnum]->lines[$i]->isLink = true;
			}
			if ($allsofar) {
				if ( true ) { // $alldata[$runnum]->lines[$i]->always) {
					$alldata[$runnum]->lines[$i]->isLink = true;
					$lnk = mklink($value, $i, $pnr, $pid, $name);
					$alldata[$runnum]->lines[$i]->link = $lnk;
				}
			}
			if ($wantlink) {
				$lnk = mklink($value, $i, $pnr, $pid, $name);
				$alldata[$runnum]->lines[$i]->link = $lnk;
				$alldata[$runnum]->atnum = $runnum;
				$alldata[$runnum]->someDone = true;
			}
		}
		if ($allsofar)
			$alldata[$runnum]->allDone = true;
	}

	return $alldata;
}


