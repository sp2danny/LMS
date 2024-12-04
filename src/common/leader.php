
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

<script>

function db_update(tp, pid, a = "", b = "") {
  var str = "db_upd.php?tp=" + tp + "&pid=" + pid;
  if (a!="") str += "&a=" + a;
  if (b!="") str += "&b=" + b;
  fetch(str);
}

</script>

</head>
<body>

<p>


Nedanstående värdegrunder tar dig till högsta nivåns självledarskap: <br> 

- Ärlighet <br> 

- Respekt <br> 

- Rättvisa <br> 

- Bidra till min och andras Personliga-, Företags, Värde, -utveckling <br> 

- Skapa trygghet <br> 

- Ha kul <br>  <br> 


Är jag sann mot mina föreställningar och värderingar? <br> 
true
Svar//  <br> 

1 Ärlighet// <br> 

2 Respekt// <br> 

3 Rättvisa// <br> 

4 Bidra till min och andras Personliga-, Företags, Värde, -utveckling// <br> 

5 Skapa trygghet// <br> 

6 Ha kul// <br>  <br> 


Beslutsmodell: När du står inför ett problem: Ställ frågan stämmer detta med värdegrund <br> 

1-6. Om ja på alla så är svaret JA. Om inte tänk igen. <br> <br>

Jag vill påminna om Michelle Obama som säger: When they go low. We go high! <br> <br>

</p>

<?php

include_once 'connect.php';
include_once 'getparam.php';

$have_vg = false;

$pid = getparam('pid');

$query = "SELECT * FROM data WHERE type='201' AND pers='$pid';";
if ($row = data_last($query)) {
	$have_vg = true;
	$vg_val = $row['value_a'];
}

echo "<hr><br><p>\n";

echo "<table><tr><td>\n";

echo "Lever jag med stolthet dessa värdegrunder: &nbsp; Svara här: &nbsp; &nbsp; &nbsp; ";

echo "\n</td><td>\n";

// <input type="range" min="0" max="100" step="25" list="steplist">

echo " <input type='range' id='vg_slide' name='vg' min='0' max='100' step='1' list='steplist' ";

if ($have_vg) {
	echo " value='$vg_val' ";
}

echo " onChange='document.getElementById(\"vg_btn\").disabled = false;' /> \n";
echo " <datalist id='steplist'> <option value='0' label='0' > </option> <option value='100' label='100' > </option> </datalist> \n";

echo "\n</td><td>\n";

echo " &nbsp; <button id='vg_btn' disabled ";
echo " onClick='document.getElementById(\"vg_btn\").disabled = true; ";
echo " db_update(201, $pid, document.getElementById(\"vg_slide\").value ); ' > Save </button> <br> \n";

echo "\n</td></tr></table>\n";

?>

</p>
</body>
</html>
