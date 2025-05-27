
<html>
<head> </head>
<body>

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
if ($res)
	echo "ok";
else
	echo "failed, " . $query;

?>

<br><br>

<a href='pgl2.php'> <button> Tillbaka </button> </a>

</body>
</html>


