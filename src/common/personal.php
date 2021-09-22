

<?php

include 'head.php';

echo <<<EOT
<style>
table tr td {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
}
table.visitab {
  border: 2px solid black;
  margin-top: 2px;
  border-collapse: collapse;
}
td.visitab {
  border: 1px solid grey;
  border-collapse: collapse;
}
</style>
EOT;


include 'common.php';
include 'connect.php';

$eol = "\n";

echo '</head><body>' . $eol;
echo '<br />' . $eol;
echo '<img width=50%  src="logo.png" /> <br />';
echo '<br /> <br />' . $eol;

function ptbl($prow)
{
	global $eol;
	echo '<table>' . $eol;
	echo '<tr> <td> Kundnummer    </td> <td> ' . $prow[ 'pers_id' ] . '</td></tr>' . $eol;
	echo '<tr> <td> Namn          </td> <td> ' . $prow[ 'name'    ] . '</td></tr>' . $eol;
	echo '<tr> <td> Personnummer  </td> <td> ' . $prow[ 'pnr'     ] . '</td></tr>' . $eol;
	echo '<tr> <td> Medlem sedan  </td> <td> ' . $prow[ 'date'    ] . '</td></tr>' . $eol;
	echo '</table>' . $eol;
}

function segments($battname)
{
	$styr = fopen('../batt-' . $battname . "/styr.txt", "r");
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

function mklink($batt, $seg, $row)
{
	return '../batt-' . $batt . '/index.php?seg=' . $seg . '&pnr=' . $row['pnr'] . '&pid=' . $row['pers_id'] . '&name='  . $row['name'] ;
}

function all()
{
	global $emperator, $eol;

	$pnr = getparam('pnr');

	$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";

	$res = mysqli_query($emperator, $query);
	$prow = false;
	$pid = 0;

	if ($prow = mysqli_fetch_array($res)) {
		ptbl($prow);
		$pid = $prow['pers_id'];
	} else {
		echo convert('Denna person hittades inte i databasen.') . " <br />" . $eol;
		return;
	}

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

	echo '<br /> <br /> <ul> ' . $eol;
	foreach ($batts as $key => $value) {
		echo '<li> ' . $value . '<ul style="list-style-type:none;" >';

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

		for ($i=1; $i<=count($segs); ++$i) {
			$thisok = false;
			if (array_key_exists($i, $done) && $done[$i])
				$thisok = true;

			$wantlink = false;
			echo '<li> <img width="12px" height="12px" src="';
			if ($thisok) {
				echo "corr";
			} else if ($allsofar) {
				echo "here";
				$allsofar = false;
				$wantlink = true;
			} else {
				echo "err";
			}
			echo '.png" > ';
			if ($wantlink)
				echo '<a href="' . mklink($value, $i, $prow) . '" > ';
			echo 'Segment ' . $i;
			if ($wantlink)
				echo ' </a> ';
			'</li>';
		}
		echo '</ul>';
	}
	echo '</ul>';

}

all();

?>

</body>
</html>
