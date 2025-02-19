
<?php

include_once "connect.php";
include_once "getparam.php";
include_once "debug.php";
include_once "common_php.php";

// function COU( $db, $id_n, $id_v, $sup_n, $sup_v, $key ) // Create Or Update


$for = getparam('pid_for');
$by = getparam('pid_by');


// 1- generate surv-id

$sid = false;

$query  = "SELECT * FROM surv WHERE name='group' AND type=209 AND pers=$for AND seq=$by";

$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res)) {
	$sid = $row['surv_id'];
}

if ($sid === false)
{
	$query  = "INSERT INTO surv (name, type, pers, seq) ";
	$query .= "VALUES ('group', 209, $for, $by); ";

	$ok = true;

	$res = mysqli_query($emperator, $query);
	if (!$res) $ok = false;

	$sid = $emperator->insert_id;
}

// create or update values

$val = getparam('st_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 301, $sid], ['value_b'], [$val], 'data_id');

$val = getparam('sv_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 302, $sid], ['value_b'], [$val], 'data_id');

$val = getparam('mo_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 303, $sid], ['value_b'], [$val], 'data_id');


// annat
$val = getparam('st_syn_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 304, $sid], ['value_b'], [$val], 'data_id');

$val = getparam('pro_soc_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 305, $sid], ['value_b'], [$val], 'data_id');

$val = getparam('st_sto_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 306, $sid], ['value_b'], [$val], 'data_id');

$val = getparam('pro_bes_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 307, $sid], ['value_b'], [$val], 'data_id');


?>

