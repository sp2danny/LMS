
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

<script>

async function utv_dosave(for_pid, by_pid)
{
	url = "save_utv.php";
	url += "?for=" + for_pid;
	url += "&by=" + by_pid;

	url += "&per_1=" + document.getElementById('per_1').value;
	url += "&per_2=" + document.getElementById('per_2').value;
	url += "&per_3=" + document.getElementById('per_3').value;

	url += "&ato_1=" + document.getElementById('ato_1').value;
	url += "&ato_2=" + document.getElementById('ato_2').value;
	url += "&ato_3=" + document.getElementById('ato_3').value;

	url += "&mmg_1=" + document.getElementById('mmg_1').value;
	url += "&mmg_2=" + document.getElementById('mmg_2').value;
	url += "&mmg_3=" + document.getElementById('mmg_3').value;

	fetch(url);

	// window.location.href = url;

}

</script>

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
		if (array_key_exists($id, $survs)) {
			$mm = min_max($survs[$id]);
			$tot += ($mm[1] - $mm[0]);
		}
	}
	return $tot;
}

function opts_f($val = 0)
{
	$opts  = '<option value=" 0" disabled ' . (($val== 0)?"selected":"")  . ' > Välj             </option> ';
	$opts .= '<option value="+2"          ' . (($val==+2)?"selected":"")  . ' > Instämmer helt   </option> ';
	$opts .= '<option value="+1"          ' . (($val==+1)?"selected":"")  . ' > Instämmer delvis </option> ';
	$opts .= '<option value="-1"          ' . (($val==-1)?"selected":"")  . ' > Invänder delvis  </option> ';
	$opts .= '<option value="-2"          ' . (($val==-2)?"selected":"")  . ' > Invänder helt    </option> ';
	return $opts;
}

function all()
{
	global $emperator, $eol;

	debug_log("utveckling " . allparam());

	$pnr = getparam('pnr');

	$gs = getparam("grpsk", false);

	$dogs = false;

	$byid = 0;
	$byn = "";

	if ($gs !== false)
	{
		$by = $pnr;
		$pnr = $gs;
		$dogs = true;

		$query = "SELECT * FROM pers WHERE pnr='" . $by . "'";

		$res = mysqli_query($emperator, $query);
		if ($prow = mysqli_fetch_array($res)) {
			$byid = $prow['pers_id'];
			$byn = $prow['name'];
		}
	}

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

	$nb = "";
	for ($i=0; $i<3; ++$i)
		$nb .= " &nbsp; ";


	if (true) // per
	{
		echo "<tr><td>" . $eol;
		echo "<img src='per.png' /> </td> <td style='width:462px;' > " . $eol;

		echo "<div> " . $eol;

		$cp_site = 'https://mind2excellence.se/site/common/per.php';
		$cp_site = addKV($cp_site, "pid", $pid);
		$cp_site = addKV($cp_site, "pnr", $pnr);

		echo ' <embed type="text/html" src="' . $cp_site . '" width="500px" height="370px" > ' . $eol;
		echo "</div> " . $eol;

		echo '</td><td> ' . $eol;
		$tot = collect_sum_diff($survs, ["positivitet", "akta", "relevans"]);
		echo "<h1> Steg Ett +$tot </h1>" . $eol;
		echo "<h3> " . $utv_ini['steg.1']['title'] . " </h3>" . $eol;
		echo $utv_ini['steg.1']['text'] . $eol;

		echo '</td></tr> ' . $eol;

		if ($dogs)
		{

			$per_1_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 321, $byid], 'value_a', 0);

			echo '<tr> <td> &nbsp; </td> ' . $eol;
			echo '<td> <select name="per_1" id="per_1" > ' . $eol;
			echo opts_f($per_1_v) . '</select> ' . $nb . $eol;
			

			$per_2_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 322, $byid], 'value_a', 0);

			echo ' <select name="per_2" id="per_2"> ' . $eol;
			echo opts_f($per_2_v)  . '</select> ' . $nb . $eol;


			$per_3_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 323, $byid], 'value_a', 0);

			echo ' <select name="per_3" id="per_3"> ' . $eol;
			echo opts_f($per_3_v) . '</select> ' . $eol;

			echo ' <td> &nbsp; </td> </tr>' . $eol;
			
		}

		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;
		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;

	}

	if (true) // ato
	{
		echo "<tr><td>" . $eol;
		echo "<img src='gen.png' /> </td> <td style='width:462px;' > " . $eol;

		echo "<div> " . $eol;

		$cp_site = 'https://mind2excellence.se/site/common/at.php';
		$cp_site = addKV($cp_site, "pid", $pid);
		$cp_site = addKV($cp_site, "pnr", $pnr);

		echo ' <embed type="text/html" src="' . $cp_site . '" width="500px" height="370px" > ' . $eol;
		echo "</div> " . $eol;

		echo '</td><td> ' . $eol;

		$tot = collect_sum_diff($survs, ["arlig", "tillit", "omdome"]);
		echo "<h1> Steg Två +$tot </h1>" . $eol;

		//echo "<h1> Steg Två </h1>" . $eol;
		echo "<h3> " . $utv_ini['steg.2']['title'] . " </h3>" . $eol;
		echo $utv_ini['steg.2']['text'] . $eol;

		echo '</td></tr>' . $eol;

		if ($dogs)
		{

			$ato_1_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 324, $byid], 'value_a', 0);

			echo '<tr> <td> &nbsp; </td> ' . $eol;
			echo '<td> <select name="ato_1" id="ato_1"> ' . $eol;
			echo opts_f($ato_1_v) . '</select> ' . $nb . $eol;


			$ato_2_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 325, $byid], 'value_a', 0);
			
			echo ' <select name="ato_2" id="ato_2"> ' . $eol;
			echo opts_f($ato_2_v) . '</select> ' . $nb . $eol;


			$ato_3_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 326, $byid], 'value_a', 0);

			echo ' <select name="ato_3" id="ato_3"> ' . $eol;
			echo opts_f($ato_3_v) . '</select> ' . $eol;

			echo ' <td> &nbsp; </td> </tr>' . $eol;
			
		}

		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;
		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;

	}

	if (true) // mmg
	{
		echo "<tr><td>" . $eol;
		echo "<img src='win.png' /> </td> <td style='width:462px;' > " . $eol;

		echo "<div> " . $eol;

		$cp_site = 'https://mind2excellence.se/site/common/mmg.php';
		$cp_site = addKV($cp_site, "pid", $pid);
		$cp_site = addKV($cp_site, "pnr", $pnr);

		echo ' <embed type="text/html" src="' . $cp_site . '" width="500px" height="370px" > ' . $eol;
		echo "</div> " . $eol;

		echo '</td><td> ' . $eol;

		$tot = collect_sum_diff($survs, ["motivation", "goal", "genomforande"]);
		echo "<h1> Steg Tre +$tot </h1>" . $eol;

		//echo "<h1> Steg Tre </h1>" . $eol;
		echo "<h3> " . $utv_ini['steg.3']['title'] . " </h3>" . $eol;
		echo $utv_ini['steg.3']['text'] . $eol;

		echo '</td></tr>' . $eol;

		if ($dogs)
		{

			$mmg_1_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 327, $byid], 'value_a', 0);

			echo '<tr> <td> &nbsp; </td> ' . $eol;
			echo '<td> <select name="mmg_1" id="mmg_1"> ' . $eol;
			echo opts_f($mmg_1_v) . '</select> ' . $nb . $eol;
			

			$mmg_2_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 328, $byid], 'value_a', 0);

			echo ' <select name="mmg_2" id="mmg_2"> ' . $eol;
			echo opts_f($mmg_2_v) . '</select> ' . $nb . $eol;

			$mmg_3_v = ROD('data', ['pers', 'type', 'value_b'], [$pid, 329, $byid], 'value_a', 0);

			echo ' <select name="mmg_3" id="mmg_3"> ' . $eol;
			echo opts_f($mmg_3_v) . '</select> ' . $eol;

			echo ' <td> &nbsp; </td> </tr>' . $eol;
			
		}

		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;
		echo '<tr> <td colspan=3> &nbsp; </td> </tr>' . $eol;

	}

	echo ' </table> ' . $eol;

	if ($dogs) {

		echo $nb . "Gruppskattning för " . $name . " <br> \n";
		echo $nb . "Utförd av " . $byn . " <br> \n";
		echo $nb . " <button onclick='utv_dosave(" . $pid . "," . $byid . ")' > Spara </button> <br> \n";
	}

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


