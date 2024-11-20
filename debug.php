
<?php

include 'site/common/head.php';
include 'site/common/common.php';
include 'site/common/connect.php';

function mklink($batt, $seg, $row)
{
	return 'site/batt-' . $batt . '/index.php?seg=' . $seg . '&pnr=' . $row['pnr'] . '&pid=' . $row['pers_id'] . '&name='  . $row['name'] ;
}

class Segment
{
	public  string  $segname;
	public  int     $segnum;
	public  string  $title;
}

function split1($str, $sep)
{
	$res = [];
	$p = strpos($str, $sep);
	if ($p === false) {
		$res[] = $str;
	} else {
		$res[] = substr($str, 0, $p);
		$res[] = substr($str, $p+1);
	}
	return $res;
}

function segments($battname)
{
	$styr = fopen('site/batt-' . $battname . "/styr.txt", "r");
	if ($styr === false) return false;

	$res = [];
	$curr = '';
	$sn = 0;
	while (true) {
		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		if ($buffer[0] == '#') continue;
		if ($buffer[0] == '!') {
			$e = split1($buffer, ' ');
			if ($e[0] == '!title')
				if ($curr != '')
					$res[$curr]->title = $e[1];
			continue;
		}

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			$res[$curr] = new Segment;
			$res[$curr]->segname = $curr;
			$res[$curr]->segnum = ++$sn;
			$res[$curr]->title = 'Del ' . $sn;
			continue;
		}
	}
	fclose($styr);
	return $res;
}

$eol = "\n";
echo '</head><body>' . $eol;
$pnr = 'debug';
$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
$res = mysqli_query($emperator, $query);
$prow = false;
$pid = 0;
if ($prow = mysqli_fetch_array($res)) {
	$pid = $prow['pers_id'];
} else {
	echo convert('Denna person hittades inte i databasen.') . " <br />" . $eol;
	return;
}

$batts = array();
$dircont = scandir("site");

foreach ($dircont as $key => $value) {
	if (strlen($value) < 5) continue;
	$a = substr($value, 0, 5);
	if ($a != 'batt-') continue;
	$a = substr($value, 5);
	$batts[] = $a;
}

echo '<br /> <br /> <ul> ' . $eol;
foreach ($batts as $key => $value) {
	echo '<li> ' . $value . '<ul style="list-style-type:none;" >';
	$segs = segments($value);
	foreach ($segs as $key => $val) {
	//for ($i=1; $i<=count($segs); ++$i) {
		echo '<li>';
		echo '<a href="' . mklink($value, $val->segnum, $prow) . '" > ';
		echo $val->title;
		echo ' </a> ';
		'</li>';
	}
	echo '</ul>';
}
echo '</ul>';

?> 

</body>
</html>


