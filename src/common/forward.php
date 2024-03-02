
<html>
<head>

<?php

include 'common.php';
include 'connect.php';
include 'roundup.php';

// required param pnr
// optional params bnum, snum

$pnr = getparam('pnr');
$bnum = getparam('bnum', 0);
$snum = getparam('snum', 0);

$pid = 0;
$name = '';

$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
$res = mysqli_query($emperator, $query);
if ($row = mysqli_fetch_array($res)) {
	$pid = $row['pers_id'];
	$name = $row['name'];
} else {
	echo "</head><body>";
	echo convert("Hittade ingen sådan person");
	echo "<br /><br /> <a href='../login.php'> <button> ";
	echo convert("Försök igen");
	echo "</button> </a>";
	echo "<br /><br /> <a href='../nypers.php'> <button> ";
	echo convert("Skapa nytt konto");
	echo "</button> </a>";
	echo "</body></html>";
	exit;
}

$ob = getparam('ob', 0);
$os = getparam('os', 0);
$ok = ($ob>0) && ($os>0);

if ($ok) {
	$query = "INSERT INTO data (pers, type, value_a, value_b) VALUES (" . $pid . ", 2, " . $ob . ", " . $os . ");";
	//$dbtext = "db-operation >>" . $query . "<< failed.\n";
	$res = mysqli_query($emperator, $query);
}

$alldata = roundup($pnr, $pid, $name);
$atnum = 0;
$link = '';

if ($bnum == 0)
{
	foreach ($alldata as $block) {
		if ($block->someDone) {
			$atnum = $block->atnum;
		}
		foreach ($block->lines as $line) {
			if ($line->isLink)
				$link = $line->link;
		}
	}
} else {
	foreach ($alldata as $block) {
		if ($block->battNum == $bnum)
		{
			$link = mklink($block->name, $snum, $pnr, $pid, $name);
		}
	}
}

// function mklink($batt, $seg, $pnr, $pid, $name)

$returnto = getparam('returnto', false);

if ($returnto)
{
	$link = '../common/' . $returnto . '.php?pnr=' . $pnr;
}

echo '<meta http-equiv="refresh" content="0; URL=';
echo $link;
echo '" />';


echo "</head><body>";
//echo $link;
echo "</body></html>";

?> 

