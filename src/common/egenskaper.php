
<!-- include egenskaper.php -->

<?php

include_once 'head.php';
include_once 'roundup.php';
include_once 'util.php';
include_once 'common_php.php';
include_once 'connect.php';
include_once 'discdisplay.php';

echo <<<EOL
<style>

.main {
	/*font-family: Arial; */
	font-size: 26px;
}

.sml {
	font-size: 11px;
}

.twm {
	padding-left : 35px;
}

.lf {
	width : 280px;
}

.ls {
	width : 320px;
}

.cn {
	text-align : center;
	font-size: 22px;
}

</style>
EOL;

function index()
{
	echo "</head><body class='main'>\n";
	
	global $emperator;
	
	$pnr = getparam("pnr", "0");
	$pid = getparam("pid", "0");

	if ($pnr != 0) {
		$query = "SELECT * FROM pers WHERE pnr='$pnr';";
		$res = mysqli_query($emperator, $query);
		if ($res)
			$prow = mysqli_fetch_array($res);
		$pid = $prow['pers_id'];
	}

	$disc = false;

	$query = "SELECT * FROM data WHERE pers='$pid' AND type='6';";
	$res = mysqli_query($emperator, $query);
	if ($res) {
		if ($row = mysqli_fetch_array($res)) {
			$disc = [];
			$disc['LR'] = $row['value_a'];
			$disc['UD'] = $row['value_b'];
		}
	}

	echo "<table><tr><td>\n";

	if ($disc)
	{
		echo disc_draw($disc['LR'], $disc['UD']);
	}
	
	echo "</td><td> \n";
	
	echo "<table><tr><td>";
	
echo "<td class='twm' >\n";
echo "Detta är mina styrkor:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 301, $i], 'value_c', '');
	echo "<input class='lf' id='st_$i' readonly type='text' value='$val' /> ";
	echo "</li>\n";
}
echo "</ul>\n<br />\n";

	echo "</td><td>";

echo "<td class='twm' >\n";
echo "Detta är mina svagheter:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 302, $i], 'value_c', '');
	echo "<input class='lf' id='sv_$i' readonly type='text' value='$val' /> ";
	echo "</li>\n";
}

echo "</ul>\n<br />\n";


	echo "</td></tr><tr><td>";

echo "<td class='twm' >\n";
echo "Detta är mina motivatorer:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 303, $i], 'value_c', '');
	echo "<input class='lf' id='mo_$i' readonly type='text' value='$val' /> ";
	echo "</li>\n";
}

echo "</ul>\n<br />\n";


	echo "</td><td>";



	echo "</td></tr></table>";

	
	echo " </td></tr></table>\n";



}




index();

?>

</body>
</html>
