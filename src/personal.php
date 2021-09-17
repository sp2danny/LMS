

<?php

include 'php/head.php';

echo <<<EOT
<style>
table.visitab {
  border: 2px solid black;
  margin-top: 2px;
  border-collapse: collapse;
}
td.visitab {
  border: 1px solid black;
  margin-top: 2px;
  border-collapse: collapse;
}
</style>
EOT;


include 'php/common.php';
include 'php/connect.php';

$eol = "\n";

echo '</head><body>' . $eol;
echo '<br />' . $eol;
echo '<img width=50%  src="logo.png" /> <br />';
echo '<br /> <br />' . $eol;

function ptbl($prow)
{
	global $eol;
	echo '<table>' . $eol;
	echo '<tr> <td> pid  </td> <td> ' . $prow[ 'pers_id' ] . '</td></tr>' . $eol;
	echo '<tr> <td> date </td> <td> ' . $prow[ 'date'    ] . '</td></tr>' . $eol;
	echo '<tr> <td> name </td> <td> ' . $prow[ 'name'    ] . '</td></tr>' . $eol;
	echo '<tr> <td> pnr  </td> <td> ' . $prow[ 'pnr'     ] . '</td></tr>' . $eol;
	echo '</table>' . $eol;
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

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			$res[$curr] = [];
			continue;
		}

		$res[$curr][] = $buffer;
	}
	return $res;
}

function all()
{
	global $emperator, $eol;

	$pnr = getparam('pnr');

	$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";

	$res = mysqli_query($emperator, $query);

	if ($row = mysqli_fetch_array($res)) {
		ptbl($row);
	} else {
		echo convert('Denna person hittades inte i databasen.') . " <br />" . $eol;
		return;
	}

	$dircont = scandir(".");

	$batts = array();

	foreach ($dircont as $key => $value) {
		if (strlen($value) < 5) continue;
		$a = substr($value, 0, 5);
		if ($a != 'batt-') continue;
		$a = substr($value, 5);
		$batts[] = $a;
	}

	echo '<br /> <br /> ' . $eol;
	if (count($batts) <= 0) {
		echo convert('Inga tillgängliga batteri.') . " <br />" . $eol;
	} else {
		echo convert('Tillgängliga batteri :') . " <br />" . $eol;
		echo '<table class="visitab" >' . $eol;
		foreach ($batts as $key => $value) {
			echo '<tr>' . $eol;
			echo '<td class="visitab" >' . $value . '</td>' . $eol;
			$segs = segments($value);
			echo '<td class="visitab" >' . count($segs) . ' segment </td>' . $eol;
			echo '</tr>' . $eol;
		}
		echo '</table>' . $eol;
	}
}

all();

?>

</body>
</html>
