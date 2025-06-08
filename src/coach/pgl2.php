
<?php

include '../site/common/head.php';

echo <<<EOT

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
  padding-bottom: 15px;
  padding-top: 15px;
  padding-left: 15px;
}
table tr td {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
  border: 1px solid black;
}
option, select {
	width: 120px;
}

</style>

<script>

function grp_add(pid, ii)
{
	url = "gp_add.php";
	url += "?pid=" + pid.toString();
	url += "&grp=" + document.getElementById('ga'+ii.toString()).value;

	window.location.href = url;
}

function grp_rem(pid, ii)
{
	url = "gp_rem.php";
	url += "?pid=" + pid.toString();
	url += "&grp=" + document.getElementById('gr'+ii.toString()).value;

	window.location.href = url;
}

</script>


EOT;

include '../site/common/common_php.php';
include '../site/common/connect.php';

$eol = "\n";

echo '</head><body>' . $eol;
echo '<br />' . $eol;
echo '<img width=50%  src="../site/common/logo.png" /> <br />';
echo '<br /> <br />' . $eol;

echo "<h1> Person Grupp Listning </h1> <br /> <br />" . $eol;

$grp = [];

function for_discard($str)
{
	//if (!$str) return true;
	if ($str == 'debug') return true;
	if ($str == 'test')  return true;
	if ($str == '')      return true;
	if ($str == 'null')  return true;
	if ($str == null)    return true;
	return false;
}

function merge($lst, $sep = ",")
{
	$frst = true;
	$ret = "";
	foreach ($lst as $s)
	{
		if (!$frst) $ret .= $sep;
		$ret .= $s;
		$frst = false;
	}
	return $ret;
}

function is_in($lst, $item)
{
	foreach ($lst as $i)
		if ($item == $i)
			return true;
	return false;
}

$gcnt = [];

function add_gc($name, $num = 1)
{
	global $gcnt;
	if (!array_key_exists($name, $gcnt))
		$gcnt[$name] = 0;
	$gcnt[$name] += $num;
}

$query = 'SELECT * FROM pers';
$res = mysqli_query($emperator, $query);
if ($res) while ($prow = mysqli_fetch_array($res))
{
	$n = $prow["name"];
	if (for_discard($n)) continue;
	$g = $prow["grupp"];
	$eg = explode(",", $g);
	foreach ($eg as $g) {
		if (for_discard($g)) continue;
		add_gc($g);
		if (!is_in($grp, $g)) {
			$grp[] = $g;
		}
	}
}

$query = 'SELECT * FROM data WHERE type=901';
$res = mysqli_query($emperator, $query);
if ($res) while ($prow = mysqli_fetch_array($res))
{
	$g = $prow["value_c"];
	if (for_discard($g)) continue;
	add_gc($g, 0);
	if (!is_in($grp, $g))
		$grp[] = $g;
}


echo "<table><tr>\n";
foreach ($grp as $g)
{
	echo "<tr>\n";
	echo "<td> ``" . $g . "´´ </td>\n";
	$gc = $gcnt[$g];
	if ($gc > 0)
		echo "<td> <a href='../site/common/grplst.php?grp=" . $g . "' > Lista </a> </td>\n";
	else
		echo "<td> <a href='../site/common/delgrp.php?grp=" . $g . "' > Ta Bort </a> </td>\n";
	echo "<td> " . $gc . " </td>\n";
	echo "</tr>\n";
}
echo "\n</table>\n";

?>

<br>

<form action="add_group.php">
  <label for="gname">Grupp namn:</label>
  <input type="text" id="gname" name="gname">
  <input type="submit" value="Lägg till">
</form> 

<br> <hr> <br>

<table>
	<tr>
		<th>pid</th>
		<th>namn</th>
		<th>pnr</th>
		<th>grupper</th>
		<th>add</th>
		<th>rem</th>
	</tr>

<?php

$ii = 0;
$query = 'SELECT * FROM pers';
$res = mysqli_query($emperator, $query);
if ($res) while ($prow = mysqli_fetch_array($res))
{
	$ii += 1;
	$n = $prow["name"];
	if (for_discard($n)) continue;
	echo "\t<tr>\n";
	$pid = $prow['pers_id'];
	echo "\t\t<td> " . $pid . " </td> \n";
	echo "\t\t<td> " . $n . " </td> \n";
	echo "\t\t<td> " . $prow['pnr'] . " </td> \n";

	$g = $prow["grupp"];
	if (for_discard($g)) $g = "";

	$gr = [];
	$grt = explode(",", $g);
	foreach ($grt as $gg) {
		if (for_discard($gg)) continue;
		if (is_in($gr, $gg)) continue;
		$gr[] = $gg;
	}

	echo "\t\t<td> " . merge($gr) . " </td> \n";


	$ga = [];
	foreach ($grp as $gg) {
		if (for_discard($gg))
			continue;
		if (array_search($gg, $gr) === false)
			$ga[] = $gg;
	}

	echo "\t\t<td> \n";
	if (count($ga)>0) {
		$nn = "ga" . $ii;
		echo "\t\t\t<select name='$nn' id='$nn'>\n";
		echo "\t\t\t\t<option selected disabled value=''> </option>\n";
		foreach ($ga as $gg) {
			echo "\t\t\t\t<option value='$gg'> $gg </option>\n";
		}
		echo "\t\t\t</select>\n";
		echo "\t\t\t<button onclick='grp_add($pid, $ii);' > + </button> \n";
	}
	echo "\t\t</td> \n";

	echo "\t\t<td> \n";
	if (count($gr)>0) {
		$nn = "gr" . $ii;
		echo "\t\t\t<select name='$nn' id='$nn'>\n";
		echo "\t\t\t\t<option selected disabled value=''> </option>\n";
		foreach ($gr as $gg) {
			echo "\t\t\t\t<option value='$gg'> $gg </option>\n";
		}
		echo "\t\t\t</select>\n";
		echo "\t\t\t<button onclick='grp_rem($pid, $ii);'> - </button> \n";
	}
	echo "\t\t</td> \n";

	echo "\t</tr>\n";
}


?>

</table>

</body>
</html>

