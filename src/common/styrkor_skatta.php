
<?php

include_once "connect.php";
include_once "getparam.php";
include_once "debug.php";
include_once "common_php.php";
include_once "debug.php";

$dbg = false;

$for = getparam('pid_for');
$by = getparam('pid_by');

// 1- generate surv-id

$sid = COR(
	"surv",
	array( "type", "pers", "name",  "seq" ),
	array( 209,    $for,   'group', $by ),
	array(), array(),
	"surv_id"
);

// create or update values

$val = getparam('st_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 301, $sid], ['value_b'], [$val], 'data_id', $dbg);

$val = getparam('sv_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 302, $sid], ['value_b'], [$val], 'data_id', $dbg);

$val = getparam('mo_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 303, $sid], ['value_b'], [$val], 'data_id', $dbg);

// annat
$val = getparam('st_syn_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 304, $sid], ['value_b'], [$val], 'data_id', $dbg);

$val = getparam('pro_soc_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 305, $sid], ['value_b'], [$val], 'data_id', $dbg);

$val = getparam('st_sto_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 306, $sid], ['value_b'], [$val], 'data_id', $dbg);

$val = getparam('pro_bes_sl');
COU('data', ['pers', 'type', 'value_a', 'surv'], [$for, 209, 307, $sid], ['value_b'], [$val], 'data_id', $dbg);


?>

