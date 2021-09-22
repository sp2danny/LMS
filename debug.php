
<?php

include 'php/head.php';
include 'php/common.php';
include 'php/connect.php';

function mklink($batt, $seg, $row)
{
	return 'batt-' . $batt . '/index.php?seg=' . $seg . '&pnr=' . $row['pnr'] . '&pid=' . $row['pers_id'] . '&name='  . $row['name'] ;
}

function segments($battname)
{
	$styr = fopen('batt-' . $battname . "/styr.txt", "r");
	if ($styr === false) return false;

	$res = [];
	$curr = '';
	$lineno = 0;
	while (true) {
		++$lineno;
		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		if ($buffer[0] == '#') continue;
		if ($buffer[0] == '!') continue;

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			$res[$curr] = [];
			continue;
		}

		$res[$curr][] = $buffer;
	}
	fclose($styr);
	return $res;
}

$eol = "\n";
echo '</head><body>' . $eol;
$pnr = '5906195697';
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
$dircont = scandir(".");

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
	for ($i=1; $i<=count($segs); ++$i) {
		echo '<li>';
		echo '<a href="' . mklink($value, $i, $prow) . '" > ';
		echo 'Del ' . $i;
		echo ' </a> ';
		'</li>';
	}
	echo '</ul>';
}
echo '</ul>';

?> 

</body>
</html>


