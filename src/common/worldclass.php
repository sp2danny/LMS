
<meta charset="UTF-8"> 

<html>
<head>
<style>

p, body, td {
	font-size: 26px;
}

datalist {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  writing-mode: vertical-lr;
  width: 170px;
}

</style>

<script>

function db_update(tp, pid, a = "", b = "") {
  var str = "db_upd.php?tp=" + tp + "&pid=" + pid;
  if (a!="") str += "&a=" + a;
  if (b!="") str += "&b=" + b;
  fetch(str);
}

function grp_sk(fr, by, id, val)
{
  var str = "grp-sk-2.php"
  str += "?fr=" + fr;
  str += "&by=" + by;
  str += "&id=" + id;
  str += "&val=" + val;
  fetch(str);
}

</script>

</head>
<body>

<p>

Lever jag i enlighet med mitt eget och Mind2Excellence´s missionstatement? <br> <br>

(Den personliga missionen beskrivs ofta i ett mindre antal meningar som ger uttryck för dina <br>

värderingar på ett positivt sätt) <br> <br>


Svar// Vi är kulturbärare för en stärkande företagskultur som skapar världsklass <br> <br>


- Vi gör varandra bra <br>

- Vi stöder varandras målsättningar <br>

- Vi sätter kärnkompetensen i fokus- <br>

- Vi skapar respekt genom att vara föredömen <br>

- Vi låter inte kunskapen skiljas från beslutsfattandet <br>

- Vi hyllar empati, etik & moral <br>

- Vi bidrar alltid med (H)ärlig energi och glädje på jobbet <br>

- Vi söker alltid synergier och utveckling <br>

- Vi levererar alltid högsta kvalité i tid <br>

- Vi är generösa, prestigelösa och tydliga i vår kommunikation <br>

- Vi sätter människan i Centrum och kundnyttan först <br>

- Vi är kärleksfullt stödjande <br>

- Det är mänskligt att göra fel (ibland) <br>

</p>


<?php

include_once 'connect.php';
include_once 'getparam.php';

$have_grp = getparam("grpsk", false);

if ($have_grp === false)
{

	$have_ms = false;

	$pid = getparam('pid');

	$query = "SELECT * FROM data WHERE type='202' AND pers='$pid';";
	if ($row = data_last($query)) {
		$have_ms = true;
		$ms_val = $row['value_a'];
	}

	echo "<hr><br><p>\n";

	echo "<table><tr><td>\n";

	echo "Lever jag med stolthet detta Missionstatement: &nbsp; Svara här: &nbsp; &nbsp; &nbsp; ";

	echo "\n</td><td>\n";

	// <input type="range" min="0" max="100" step="25" list="steplist">

	echo "  <input type='range' id='ms_slide' name='ms' min='0' max='100' step='1' list='steplist'  ";

	if ($have_ms) {
		echo " value='$ms_val' ";
	}

	echo " onChange='document.getElementById(\"ms_btn\").disabled = false;' /> \n";
	echo " <datalist id='steplist'> <option value='0' label='0' > </option> <option value='100' label='100' > </option> </datalist> \n";

	echo "\n</td><td>\n";

	echo " &nbsp; <button id='ms_btn' disabled ";
	echo " onClick='document.getElementById(\"ms_btn\").disabled = true; ";
	echo " db_update(202, $pid, document.getElementById(\"ms_slide\").value ); ' > Save </button> <br> \n";

	echo "\n</td></tr></table>\n";

} else { // -----------------------------------------------------------------------------------------------------------

	$pnr_for = $have_grp;

	$query = "SELECT * FROM pers WHERE pnr='$pnr_for'";
	$res = mysqli_query( $emperator, $query );
	if ($res) if ($row = mysqli_fetch_array($res)) {
		$pid_for = $row['pers_id'];
		$name_for = $row['name'];
	}

	$pnr_by = getparam("pnr");
	$query = "SELECT * FROM pers WHERE pnr='$pnr_by'";
	$res = mysqli_query( $emperator, $query );
	if ($res) if ($row = mysqli_fetch_array($res)) {
		$pid_by = $row['pers_id'];
		$name_by = $row['name'];
	}

	$have_ms = false;
	$query = "SELECT * FROM data WHERE type='202' AND pers='$pid_for';";
	if ($row = data_last($query)) {
		$have_ms = true;
		$ms_val = $row['value_a'];
	}

	echo "<hr><p>\n";

	echo "<code>\n";
	echo "  Gruppskattning för " . $name_for . " <br> \n";
	echo "  Utförd av " . $name_by . " <br> \n";
	echo "</code>\n";

	echo "<hr>\n";

	if (!$have_ms)
	{
		echo "<br> egenskattning ej utförd <br>";
	} else {

		
		echo "<br> <table><tr><td colspan=2><p>\n";

		echo "Lever " . $name_for . " med stolthet detta Missionstatement: &nbsp; &nbsp; ";

		echo "\n</td></tr><tr><td colspan=2>\n";

		echo " &nbsp; \n";
		
		echo "\n</td></tr><tr><td>\n";
		

		echo "Egenskattning av " . $name_for . " &nbsp; &nbsp; ";

		echo "\n</td><td>\n";

		echo " <input disabled=true type='range' min='0' max='100' step='1' list='steplist' ";

		if ($have_ms) {
			echo " value='$ms_val' ";
		}

		echo " readonly /> \n";
		echo " <datalist id='steplist'> <option value='0' label='0' > </option> <option value='100' label='100' > </option> </datalist> \n";

		echo "\n</td></tr><tr><td>\n";


		echo "Din skattning här: &nbsp; &nbsp; &nbsp; ";

		echo "\n</td><td>\n";

		echo " <input type='range' id='ms_slide' name='ms' min='0' max='100' step='1' list='steplist' ";

		if ($have_ms) {
			echo " value='$ms_val' ";
		}

		echo " onChange='document.getElementById(\"ms_btn\").disabled = false;' /> \n";
		echo " <datalist id='steplist'> <option value='0' label='0' > </option> <option value='100' label='100' > </option> </datalist> \n";

		echo "\n</p></td><td>\n";

		echo " &nbsp; <button id='ms_btn' disabled ";
		echo " onClick='document.getElementById(\"ms_btn\").disabled = true; ";
		echo " grp_sk($pid_for, $pid_by, 202, document.getElementById(\"ms_slide\").value ); ' ";
		echo " > Save </button> <br> \n";

		echo "\n</td></tr></table>\n";


	}

}

?>

</p>

</body>
</html>

