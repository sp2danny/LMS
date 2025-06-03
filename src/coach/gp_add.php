
<?php

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
if ($g!="") $g .= ",";
$g .= $grp;

$query = "UPDATE pers SET grupp='$g' WHERE pers_id=$pid;";

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
	echo "<head> <title> Grupp Add </title> </head>\n";
	echo "<body>\n";
	echo "failed, " . $query . "\n";
	echo "<br><br>\n";
	echo "<a href='pgl2.php'> <button> Tillbaka </button> </a>\n";
	echo "</body>\n";
	echo "</html>\n";
}

?>

<br><br>

<a href='pgl2.php'> <button> Tillbaka </button> </a>

</body>
</html>


