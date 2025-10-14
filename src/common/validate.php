
<?php

include_once 'common.php';

$pid = getparam("pid",0);

flush();

include_once 'connect.php';
include_once 'get_gr_val.php';
include_once 'util.php';

$grp = "";

$query = "SELECT * FROM pers WHERE pers_id=" . $pid;
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res))
	$grp = $row['grupp'];

$pidlst = [];

$query = "SELECT * FROM pers";
$res = mysqli_query( $emperator, $query );
if ($res) while ($row = mysqli_fetch_array($res))
{
	if (!arr_overlap($grp, $row['grupp'])) continue;

	$pp = $row['pers_id'];

	if ($pp == $pid) continue;

	$pidlst[] = $pp;
}

$val_cnt = 0;

foreach ($pidlst as $pp)
{
	$for = $pid;
	$by = $pp;

	$rr = true;

	$rr = $rr && get_gr_val($by, $for, 209);

	$rr = $rr && get_gr_val($by, $for, 202);

	if ($rr) $val_cnt += 1;
}

// <img src="source of image" alt="alternative text" title="this will be displayed as a tooltip"/>

$tt = '<img style="float:left;" height="90px" ';

if ( ($pid==16) || ($pid==15) )
{
	$tt .= 'src="guld.png" ';
} else {
	$tt .= 'src="silver.png" ';
}

$tt .= "title='$val_cnt valideringar' ";

$tt .= " /> ";

echo $tt;

?>
