
<meta charset="UTF-8"> 

<html>
<head>
<style>

p {
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

$have_ms = false;

$pid = getparam('pid');

$query = "SELECT * FROM data WHERE type='202' AND pers='$pid';";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
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

echo " onChange='document.getElementById(\"ms_btn\").disabled = false;' /> ";
echo " <datalist id='steplist'> <option value='0' label='0' > </option> <option value='100' label='100' > </option> </datalist> \n";

echo "\n</td><td>\n";

echo " &nbsp; <button id='ms_btn' disabled onClick='document.getElementById(\"ms_btn\").disabled = true;' > Save </button> <br> \n";

echo "\n</td></tr></table>\n";

?>


</p>
</body>
</html>

