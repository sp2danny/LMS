<!doctype html>

<html>
<head>

<meta charset="UTF-8" />

<title> Styrkor </title>

<?php

include_once "getparam.php";
include_once "connect.php";
include_once "common_php.php";

?>

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

<script>

function OnChangeHandler()
{
	const elem = document.getElementById("SaveBtn");
	elem.disabled = false;
}

function slu(pf)
{
	const div = document.getElementById(pf + "_div");
	const sl = document.getElementById(pf + "_sl");
	div.innerHTML = " " + sl.value.toString() + "%";
}

function DoUpdateDivs()
{
	slu("st");
	slu("sv");
	slu("mo");
	//OnChangeHandler();
}

function andraSliders()
{
	slu('st_syn');
	slu('pro_soc');
	slu('st_sto');
	slu('pro_bes');
	OnChangeHandler();
}

function OnChangeSlider()
{
	DoUpdateDivs();
	OnChangeHandler();
}

function SaveBtnPressSkatta(pid_by, pid_for)
{
	
	url = "styrkor_skatta.php";
	url += "?pid_by=" + pid_by;
	url += "&pid_for=" + pid_for;

	url += "&st_sl=" + document.getElementById("st_sl").value;

	url += "&sv_sl=" + document.getElementById("sv_sl").value;

	url += "&mo_sl=" + document.getElementById("mo_sl").value;

	url += "&st_syn_sl="  + document.getElementById("st_syn_sl").value;
	url += "&pro_soc_sl=" + document.getElementById("pro_soc_sl").value;
	url += "&st_sto_sl="  + document.getElementById("st_sto_sl").value;
	url += "&pro_bes_sl=" + document.getElementById("pro_bes_sl").value;

	fetch(url);

	document.getElementById("SaveBtn").disabled = true;

}

function SaveBtnPress(pid)
{
	
	url = "styrkor_save.php?pid=" + pid;
	
	url += "&st_1=" + document.getElementById("st_1").value;
	url += "&st_2=" + document.getElementById("st_2").value;
	url += "&st_3=" + document.getElementById("st_3").value;
	url += "&st_4=" + document.getElementById("st_4").value;
	url += "&st_5=" + document.getElementById("st_5").value;

	url += "&st_sl=" + document.getElementById("st_sl").value;


	url += "&sv_1=" + document.getElementById("sv_1").value;
	url += "&sv_2=" + document.getElementById("sv_2").value;
	url += "&sv_3=" + document.getElementById("sv_3").value;
	url += "&sv_4=" + document.getElementById("sv_4").value;
	url += "&sv_5=" + document.getElementById("sv_5").value;

	url += "&sv_sl=" + document.getElementById("sv_sl").value;


	url += "&mo_1=" + document.getElementById("mo_1").value;
	url += "&mo_2=" + document.getElementById("mo_2").value;
	url += "&mo_3=" + document.getElementById("mo_3").value;
	url += "&mo_4=" + document.getElementById("mo_4").value;
	url += "&mo_5=" + document.getElementById("mo_5").value;

	url += "&mo_sl=" + document.getElementById("mo_sl").value;
	

	url += "&st_syn_sl="  + document.getElementById("st_syn_sl").value;
	url += "&pro_soc_sl=" + document.getElementById("pro_soc_sl").value;
	url += "&st_sto_sl="  + document.getElementById("st_sto_sl").value;
	url += "&pro_bes_sl=" + document.getElementById("pro_bes_sl").value;


	fetch(url);

	document.getElementById("SaveBtn").disabled = true;
	
	//document.getElementById("replaceme").innerHTML = url;
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

<div class='main'>

<br /> <br />

<?php

$have_grp = getparam("grpsk", false);

$pnr = getparam("pnr", "0");
$pid = getparam("pid", "0");

if ($pnr!=0)
	$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";
if ($pid!=0)
	$query = "SELECT * FROM pers WHERE pers_id='" .$pid . "'";

$res = mysqli_query($emperator, $query);
$name = '';

if (!$res)
{
	echo 'DB Error';
} else {
	$prow = mysqli_fetch_array($res);
	if (!$prow) {
		echo 'DB Error';
	} else {
		$pnr = $prow['pnr'];
		$pid = $prow['pers_id'];
		$name = $prow['name'];
	}
}

if ($have_grp === false)
{

	echo <<<EOT
	<table> <tr>

	<td> <img style="width: 682px; height: 511px" src="styrkor.png" /> </td>

	<td> <img style="width: 682px; height: 511px" src="proaktiv.jpg" /> </td>

	</tr> <tr>

	<td class="cn" >
	<span> Så här bra är jag på att hitta synergier med andras styrkor: </span> <span id="st_syn_div" > </span>
	<br />
	&nbsp;&nbsp;&nbsp; <input class='ls' id='st_syn_sl' type='range' onChange='andraSliders()' />
	</td>

	<td class="cn" >
	<span> Så här bra är jag på att vara socialt proaktiv: </span> <span id="pro_soc_div" > </span>
	<br />
	&nbsp;&nbsp;&nbsp; <input class='ls' id='pro_soc_sl' type='range' onChange='andraSliders()' />
	</td>

	</tr> <tr>

	<td class="cn" >
	<span> Så här bra är jag på att stötta andras svagheter: </span> <span id="st_sto_div" > </span>
	<br />
	&nbsp;&nbsp;&nbsp; <input class='ls' id='st_sto_sl' type='range' onChange='andraSliders()' />
	</td>

	<td class="cn" >
	<span> Så här bra är jag på att fatta proaktiva beslut: </span> <span id="pro_bes_div" > </span>
	<br />
	&nbsp;&nbsp;&nbsp; <input class='ls' id='pro_bes_sl' type='range' onChange='andraSliders()' />
	</td>

	</tr>
	</table>
EOT;




	echo "<table><tr>\n";


	// STYRKOR

	echo "<td class='twm' >\n";
	echo "Detta är mina styrkor:\n<ul>\n";

	for ($i=1; $i<=5; ++$i)
	{
		echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
		$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 301, $i], 'value_c', '');
		echo "<input class='lf' id='st_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
		echo "</li>\n";
	}

	echo "</ul>\n<br />\n";

	echo "<span> Så här bra är jag på att utnyttja min styrkor: </span> <span id='st_div'> </span> \n";
	echo "<br /> &nbsp;&nbsp;&nbsp; ";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 301, 0], 'value_b', 0);
	echo "<input class='ls' id='st_sl' onchange='OnChangeSlider()' type='range' value='$val' /> \n";

	echo "</td>\n";


	// SVAGHETER

	echo "<td class='twm' >\n";
	echo "Detta är mina svagheter:\n<ul>\n";

	for ($i=1; $i<=5; ++$i)
	{
		echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
		$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 302, $i], 'value_c', '');
		echo "<input class='lf' id='sv_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
		echo "</li>\n";
	}

	echo "</ul>\n<br />\n";

	echo "<span> Så här bra är jag på att be om hjälp: </span> <span id='sv_div'> </span> \n";
	echo "<br /> &nbsp;&nbsp;&nbsp; ";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 302, 0], 'value_b', 0);
	echo "<input class='ls' id='sv_sl' onchange='OnChangeSlider()' type='range' value='$val' /> \n";

	echo "</td>\n";


	// MOTIVATORER

	echo "<td class='twm' >\n";
	echo "Detta är mina motivatorer:\n<ul>\n";

	for ($i=1; $i<=5; ++$i)
	{
		echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
		$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 303, $i], 'value_c', '');
		echo "<input class='lf' id='mo_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
		echo "</li>\n";
	}

	echo "</ul>\n<br />\n";

	echo "<span> Så här bra är jag på att hitta motivation: </span> <span id='mo_div'> </span> \n";
	echo "<br /> &nbsp;&nbsp;&nbsp; ";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 303, 0], 'value_b', 0);
	echo "<input class='ls' id='mo_sl' onchange='OnChangeSlider()' type='range' value='$val' /> \n";

	echo "</td>\n";


	echo "</tr></table>\n";

	echo "<br /><hr /><br />\n";

	echo "<button disabled id='SaveBtn' onClick='SaveBtnPress($pid)' > Save </button>\n";

	echo "<script>\n";
	echo "DoUpdateDivs();\n";

	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 300, 1], 'value_b', 0);
	echo "document.getElementById('st_syn_sl').value = $val;\n";

	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 300, 2], 'value_b', 0);
	echo "document.getElementById('pro_soc_sl').value = $val;\n";

	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 300, 3], 'value_b', 0);
	echo "document.getElementById('st_sto_sl').value = $val;\n";

	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 300, 4], 'value_b', 0);
	echo "document.getElementById('pro_bes_sl').value = $val;\n";


	echo "</script>\n";

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


	echo "<br><code>\n";
	echo "  Gruppskattning för " . $name_for . " <br> \n";
	echo "  Utförd av " . $name_by . " <br> \n";
	echo "</code><br>\n";


	echo <<<EOT

	<table> <tr>

	<td> <img style="width: 682px; height: 511px" src="styrkor.png" /> </td>

	<td> <img style="width: 682px; height: 511px" src="proaktiv.jpg" /> </td>

	</tr> 
EOT;


	echo "<tr>\n";

	echo "<td class='cn' >\n";
	echo "<span> Så här bra är $name_for på att hitta synergier med andras styrkor: </span> <span id='st_syn_div' > </span>\n";
	echo "<br />\n";
	echo "Egenskattning: <input class='ls' id='st_syn_sl_e' type='range' readonly disabled=true /> <br>\n";
	echo "Din Skattning: <input class='ls' id='st_syn_sl' type='range' onChange='andraSliders()' />\n";
	echo "</td>\n";

	echo "<td class='cn' >\n";
	echo "<span> Så här bra är $name_for på att vara socialt proaktiv: </span> <span id='pro_soc_div' > </span>\n";
	echo "<br />\n";
	echo "Egenskattning: <input class='ls' id='pro_soc_sl_e' type='range' readonly disabled=true /> <br>\n";
	echo "Din Skattning: <input class='ls' id='pro_soc_sl' type='range' onChange='andraSliders()' />\n";
	echo "</td>\n";

	echo "</tr> <tr>\n";

	echo "<td class='cn' >\n";
	echo "<span> Så här bra är $name_for på att stötta andras svagheter: </span> <span id='st_sto_div' > </span>\n";
	echo "<br />\n";
	echo "Egenskattning: <input class='ls' id='st_sto_sl_e' type='range' readonly disabled=true /> <br>\n";
	echo "Din Skattning: <input class='ls' id='st_sto_sl' type='range' onChange='andraSliders()' />\n";
	echo "</td>\n";

	echo "<td class='cn' >\n";
	echo "<span> Så här bra är $name_for på att fatta proaktiva beslut: </span> <span id='pro_bes_div' > </span>\n";
	echo "<br />\n";
	echo "Egenskattning: <input class='ls' id='pro_bes_sl_e' type='range' readonly disabled=true /> <br>\n";
	echo "Din Skattning: <input class='ls' id='pro_bes_sl' type='range' onChange='andraSliders()' />\n";
	echo "</td>\n";

	echo "</tr>\n";
	echo "</table>\n";

	echo "<table><tr>\n";


	// STYRKOR

	echo "<td class='twm' >\n";
	echo "Detta är $name_for's styrkor:\n<ul>\n";

	for ($i=1; $i<=5; ++$i)
	{
		echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
		$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 301, $i], 'value_c', '');
		echo "<input readonly class='lf' id='st_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
		echo "</li>\n";
	}

	echo "</ul>\n<br />\n";

	echo "<span> Så här bra är $name_for på att utnyttja sina styrkor: </span> <span id='st_div'> </span> \n";
	echo "<br /> &nbsp;&nbsp;&nbsp; ";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 301, 0], 'value_b', 0);
	echo "Egenskattning: <input class='ls' id='st_sl_e' type='range' readonly disabled=true value='$val' /> <br>\n";
	echo "Din Skattning: <input class='ls' id='st_sl' type='range' onChange='DoUpdateDivs(); OnChangeHandler();' value='$val' />\n";

	echo "</td>\n";


	// SVAGHETER

	echo "<td class='twm' >\n";
	echo "Detta är $name_for's svagheter:\n<ul>\n";

	for ($i=1; $i<=5; ++$i)
	{
		echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
		$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 302, $i], 'value_c', '');
		echo "<input readonly class='lf' id='sv_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
		echo "</li>\n";
	}

	echo "</ul>\n<br />\n";

	echo "<span> Så här bra är $name_for på att be om hjälp: </span> <span id='sv_div'> </span> \n";
	echo "<br /> &nbsp;&nbsp;&nbsp; ";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 302, 0], 'value_b', 0);
	echo "Egenskattning: <input class='ls' id='sv_sl_e' type='range' readonly disabled=true value='$val' /> <br>\n";
	echo "Din Skattning: <input class='ls' id='sv_sl' type='range' onChange='DoUpdateDivs(); OnChangeHandler();' value='$val' />\n";

	echo "</td>\n";


	// MOTIVATORER

	echo "<td class='twm' >\n";
	echo "Detta är $name_for's motivatorer:\n<ul>\n";

	for ($i=1; $i<=5; ++$i)
	{
		echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
		$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 303, $i], 'value_c', '');
		echo "<input readonly class='lf' id='mo_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
		echo "</li>\n";
	}

	echo "</ul>\n<br />\n";

	echo "<span> Så här bra är $name_for på att hitta motivation: </span> <span id='mo_div'> </span> \n";
	echo "<br /> &nbsp;&nbsp;&nbsp; ";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 303, 0], 'value_b', 0);
	echo "Egenskattning: <input class='ls' id='mo_sl_e' type='range' readonly disabled=true value='$val' /> <br>\n";
	echo "Din Skattning: <input class='ls' id='mo_sl' type='range' onChange='DoUpdateDivs(); OnChangeHandler();' value='$val' />\n";

	echo "</td>\n";

	echo "</tr></table>\n";

	echo "<br /><hr /><br />\n";

	echo "<code>\n";
	echo "  Gruppskattning för " . $name_for . " <br> \n";
	echo "  Utförd av " . $name_by . " <br> \n";
	echo "</code>\n";

	echo "<br /><hr /><br />\n";

	echo "<button disabled id='SaveBtn' onClick='SaveBtnPressSkatta($pid_by, $pid_for)' > Save </button>\n";

	echo "<script>\n";
	echo "DoUpdateDivs();\n";

	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 300, 1], 'value_b', 0);
	echo "document.getElementById('st_syn_sl').value = $val;\n";
	echo "document.getElementById('st_syn_sl_e').value = $val;\n";

	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 300, 2], 'value_b', 0);
	echo "document.getElementById('pro_soc_sl').value = $val;\n";
	echo "document.getElementById('pro_soc_sl_e').value = $val;\n";

	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 300, 3], 'value_b', 0);
	echo "document.getElementById('st_sto_sl').value = $val;\n";
	echo "document.getElementById('st_sto_sl_e').value = $val;\n";

	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid_for, 300, 4], 'value_b', 0);
	echo "document.getElementById('pro_bes_sl').value = $val;\n";
	echo "document.getElementById('pro_bes_sl_e').value = $val;\n";

	echo "</script>\n";

}

?>

<div class='sml' id='replaceme'> </div>

</div>

</body>
</html>

