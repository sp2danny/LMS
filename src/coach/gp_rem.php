
<?php

function for_discard($str)
{
	if (!$str) return true;
	if ($str == 'debug') return true;
	if ($str == 'test')  return true;
	if ($str == '')      return true;
	if ($str == 'null')  return true;
	if ($str == null)    return true;
	return false;
}

include '../site/common/common_php.php';
include '../site/common/connect.php';

$pid = getparam("pid", false);
$grp = getparam("grp", false);

$query = 'SELECT * FROM pers WHERE pers_id=' . $pid;
$res = mysqli_query($emperator, $query);
$g = "";
if ($res) if ($prow = mysqli_fetch_array($res))
	$g = $prow['grupp'];
if (!$g) $g = "";
$gg = explode(",", $g);
$ng = "";
foreach ($gg as $g)
{
	if (for_discard($g)) continue;
	if ($g == $grp) continue;
	if ($ng != "") $ng .= ",";
	$ng .= $g;
}

$query = "UPDATE pers SET grupp='$ng' WHERE pers_id=$pid;";

$res = mysqli_query($emperator, $query);
if ($res) {
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv='refresh' content='0; url=pgl2.php' />\n";
	echo "</head><body>\n";
	echo "<a href='pgl2.php'> <button> Tillbaka </button> </a>\n";
	echo "</body>\n";
	echo "</html>\n";
} else {
	echo "<html>\n";
	echo "<head> <title> Grupp Rem </title> </head>\n";
	echo "<body>\n";
	echo "failed, " . $query . "\n";
	echo "<br><br>\n";
	echo "<a href='pgl2.php'> <button> Tillbaka </button> </a>\n";
	echo "</body>\n";
	echo "</html>\n";
}

?>


