
<!-- inlude utveckling.php -->

<?php

include_once 'head.php';
include_once 'roundup.php';
include_once 'util.php';
include_once 'stapel_disp.php';
include_once 'common.php';
include_once 'connect.php';

echo <<<EOT

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body.nomarg {
    background-color: #ffffff;
    margin-top: 5px;
    margin-right: 5px;
    margin-left: 5px;
    margin-bottom: 5px;
}

table tr td {
  padding-left:   11px;
  padding-right:  11px;
  padding-top:    1px;
  padding-bottom: 1px;
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

.collapsible {
  background-color: #FFF;
  color: black;
  cursor: pointer;
  padding: 8px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.collapsible:hover {
  background-color: #EEE;
}
.content {
  padding: 3px 8px;
  display: none;
  overflow: hidden;
  background-color: white;
}
</style>
EOT;

$eol = "\n";

echo '</head><body class="nomarg" >' . $eol;

// echo '<hr />' . $eol;

function ptbl($prow, $mynt, $score=0)
{
	//global $eol;
	//echo '<table>' . $eol;
	//echo '<tr> <td> Kundnummer    </td> <td> ' . $prow[ 'pers_id' ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> Guldmynt     </td> <td> ' . $mynt   . '</td></tr>' . $eol;
	//echo '<tr> <td> Namn          </td> <td> ' . $prow[ 'name'    ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> Po&auml;ng   </td> <td> ' . $score  . '</td></tr>' . $eol;
	//echo '<tr> <td> Personnummer  </td> <td> ' . $prow[ 'pnr'     ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> </td> <td> </td> </tr>' . $eol;
	//echo '<tr> <td> Medlem sedan  </td> <td> ' . $prow[ 'date'    ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> </td> <td> </td> </tr>' . $eol;
	//echo '</table>' . $eol;
	//echo '<hr />' . $eol;

}

function min_max($arr)
{
	$have = false;
	$min = $max = 0;
	foreach ($arr as $val)
	{
		if (!$have) {
			$min = $max = $val;
			$have = true;
		} else {
			if ($val < $min) $min = $val;
			if ($val > $max) $max = $val;
		}
	}
	return [$min, $max];
}

function collect_sum_diff($survs, $ids)
{
	$tot = 0;
	foreach ($ids as $id)
	{
		$mm = min_max($survs[$id]);
		$tot += ($mm[1] - $mm[0]);
	}
	return $tot;
}

function all()
{
	global $emperator, $eol;

	$pnr = getparam('pnr');

	$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";

	$res = mysqli_query($emperator, $query);
	$prow = false;
	$pid = 0;
	$name = '';

	if ($prow = mysqli_fetch_array($res)) {

		$query = 'SELECT * FROM data WHERE pers=' . $prow['pers_id'] . ' AND type=4';
		$res = mysqli_query($emperator, $query);
		$mynt = 0;
		if ($row = mysqli_fetch_array($res))
			$mynt = $row['value_a'];

		ptbl($prow, $mynt);
		$pid = $prow['pers_id'];
		$name = $prow['name'];
	} else {
		echo convert('Denna person hittades inte i databasen.') . " <br />" . $eol;
		return;
	}

	$utv_file = fopen("utv.txt", "r");
	$utv_ini = readini($utv_file);

	echo "<table>" . $eol;
	
	$survs = collect_stapel_all($pid);

	if (true) // per
	{
		echo "<tr><td>" . $eol;
		echo "<img src='per.png' /> </td> <td style='width:462px;' > " . $eol;

		echo "<div> " . $eol;

		$cp_site = 'https://mind2excellence.se/site/common/per.php';
		$cp_site = addKV($cp_site, "pid", $pid);
		$cp_site = addKV($cp_site, "pnr", $pnr);

		echo ' <embed type="text/html" src="' . $cp_site . '" width="462px" height="296px" > ' . $eol;
		echo "</div> " . $eol;

		echo '</td><td> ' . $eol;
		$tot = collect_sum_diff($survs, ["positivitet", "akta", "relevans"]);
		echo "<h1> Steg Ett +$tot </h1>" . $eol;
		echo "<h3> " . $utv_ini['steg.1']['title'] . " </h3>" . $eol;
		echo $utv_ini['steg.1']['text'] . $eol;

		echo '</td></tr> ' . $eol;

		//echo '<tr> <td colspan=3> <center> text med länk </center> </td></tr> ' . $eol;

		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;
	}

	if (true) // at
	{
		echo "<tr><td>" . $eol;
		echo "<img src='gen.png' /> </td> <td style='width:462px;' > " . $eol;

		echo "<div> " . $eol;

		$cp_site = 'https://mind2excellence.se/site/common/at.php';
		$cp_site = addKV($cp_site, "pid", $pid);
		$cp_site = addKV($cp_site, "pnr", $pnr);

		echo ' <embed type="text/html" src="' . $cp_site . '" width="462px" height="296px" > ' . $eol;
		echo "</div> " . $eol;

		echo '</td><td> ' . $eol;

		$tot = collect_sum_diff($survs, ["arlig", "tillit", "omdome"]);
		echo "<h1> Steg Två +$tot </h1>" . $eol;

		//echo "<h1> Steg Två </h1>" . $eol;
		echo "<h3> " . $utv_ini['steg.2']['title'] . " </h3>" . $eol;
		echo $utv_ini['steg.2']['text'] . $eol;

		echo '</td></tr>' . $eol;

		//echo '<tr> <td colspan=3> <center> text med länk </center> </td></tr> ' . $eol;

		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;

	}

	if (true) // win
	{
		echo "<tr><td>" . $eol;
		echo "<img src='win.png' /> </td> <td style='width:462px;' > " . $eol;

		echo "<div> " . $eol;

		$cp_site = 'https://mind2excellence.se/site/common/mmg.php';
		$cp_site = addKV($cp_site, "pid", $pid);
		$cp_site = addKV($cp_site, "pnr", $pnr);

		echo ' <embed type="text/html" src="' . $cp_site . '" width="462px" height="296px" > ' . $eol;
		echo "</div> " . $eol;

		echo '</td><td> ' . $eol;

		$tot = collect_sum_diff($survs, ["motivation", "goal", "genomforande"]);
		echo "<h1> Steg Tre +$tot </h1>" . $eol;

		//echo "<h1> Steg Tre </h1>" . $eol;
		echo "<h3> " . $utv_ini['steg.3']['title'] . " </h3>" . $eol;
		echo $utv_ini['steg.3']['text'] . $eol;

		echo '</td></tr>' . $eol;

		//echo '<tr> <td colspan=3> <center> text med länk </center> </td></tr> ' . $eol;

		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;
		
		

	}

	echo ' </table> ' . $eol;

	echo '<hr />' . $eol;

	$dagens = array();
	$ord = fopen("ord.txt", "r");
	if ($ord)
	{
		while (true) {
			$buffer = fgets($ord, 4096);
			if (!$buffer) break;
			$buffer = trim($buffer);
			$len = strlen($buffer);
			if ($len == 0) continue;
			$cc = 0;
			for ($idx=0; $idx<$len; ++$idx)
				$cc = $cc ^ ord($buffer[$idx]);
			if ($len != 105 || $cc != 8)
				$dagens[] = $buffer;
		}
	}

	echo $utv_ini['botten']['text'] . $eol;

}

all();

?>

</body>
</html>


