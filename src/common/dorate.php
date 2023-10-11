
<?php

include 'connect.php';
include 'getparam.php';

$pnr = getparam('pnr');
$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
$res = mysqli_query($emperator, $query);
$row = mysqli_fetch_array($res);
$pid = $row['pers_id'];
$batt = getparam('bnum');
$sc = getparam('score');

$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $row['pers_id'] . ", 15, " . $batt . ", " . $sc . ");";
$res = mysqli_query($emperator, $query);

echo "<html><head>";
$lnk = "personal.php?pnr=" . $pnr;
echo "<meta http-equiv='refresh' content='0; url=" . '"' . $lnk . '"' . "' />\n";

echo "</head><body>";
echo "<a href='" . $lnk . "' > next </a>";
echo " </body> </html> ";
