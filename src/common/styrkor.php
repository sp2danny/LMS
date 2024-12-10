<!doctype html>

<html>
<head>

<meta charset="UTF-8" />

<title> Styrkor </title>

<?php

include_once "getparam.php";
include_once "connect.php";
include_once "common.php";

?>

<style>

.main {
	/*font-family: Arial; */
	font-size: 26px;
}

.twm {
	padding-left : 35px;
}

</style>

<script>

function OnChangeHandler()
{
	const elem = document.getElementById("SaveBtn");
	elem.disabled = false;
}

function SaveBtnPress()
{
	const elem = document.getElementById("SaveBtn");
	elem.disabled = true;
}


</script>


</head>
<body>

<div class='main'>

<img src="styrkor.png" />

<br /> <br />

<?php


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

echo "<table><tr>\n";


// STYRKOR

echo "<td class='twm' >\n";
echo "Detta är mina styrkor:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 301, $i], 'value_c', '');
	echo "<input id='st_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
	echo "</li>\n";
}

echo "</ul>\n<br />\n";

echo "<label for='st_sl'> Så här bra är jag på att utnyttja min styrkor: </label>\n";
echo "<br /> &nbsp;&nbsp;&nbsp; ";
echo "<input id='st_sl' onchange='OnChangeHandler()' type='range' value='0' /> \n";

echo "</td>\n";


// SVAGHETER

echo "<td class='twm' >\n";
echo "Detta är mina svagheter:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 302, $i], 'value_c', '');
	echo "<input id='sv_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
	echo "</li>\n";
}

echo "</ul>\n<br />\n";

echo "<label for='sv_sl'> Så här bra är jag på att be om hjälp: </label>\n";
echo "<br /> &nbsp;&nbsp;&nbsp; ";
echo "<input id='sv_sl' onchange='OnChangeHandler()' type='range' value='0' /> \n";

echo "</td>\n";


// MOTIVATORER

echo "<td class='twm' >\n";
echo "Detta är mina motivatorer:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 303, $i], 'value_c', '');
	echo "<input id='mo_$i' onchange='OnChangeHandler()' type='text' value='$val' /> ";
	echo "</li>\n";
}

echo "</ul>\n<br />\n";

echo "<label for='mo_sl'> Så här bra är jag på att hitta motivation: </label>\n";
echo "<br /> &nbsp;&nbsp;&nbsp; ";
echo "<input id='mo_sl' onchange='OnChangeHandler()' type='range' value='0' /> \n";

echo "</td>\n";





echo "</tr></table>\n";

echo "<br /><hr /><br />\n";

echo "<button disabled id='SaveBtn' onClick='SaveBtnPress()' > Save </button>\n"

?>

</div>

</body>
</html>

