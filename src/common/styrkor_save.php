
<?php

include_once "connect.php";
include_once "getparam.php";
include_once "debug.php";
include_once "common_php.php";

// function COU( $db, $id_n, $id_v, $sup_n, $sup_v, $key ) // Create Or Update


$pid = getparam('pid');

// styrkor
for ($i=1; $i<=5; ++$i)
{
	$val = getparam('st_' . $i);
	COU('data', ['pers', 'type', 'value_a'], [$pid, 301, $i], ['value_c'], [$val], 'data_id');
}
$val = getparam('st_sl');
COU('data', ['pers', 'type', 'value_a'], [$pid, 301, 0], ['value_b'], [$val], 'data_id');


// svagheter
for ($i=1; $i<=5; ++$i)
{
	$val = getparam('sv_' . $i);
	COU('data', ['pers', 'type', 'value_a'], [$pid, 302, $i], ['value_c'], [$val], 'data_id');
}
$val = getparam('sv_sl');
COU('data', ['pers', 'type', 'value_a'], [$pid, 302, 0], ['value_b'], [$val], 'data_id');

	
// motivatorer
for ($i=1; $i<=5; ++$i)
{
	$val = getparam('mo_' . $i);
	COU('data', ['pers', 'type', 'value_a'], [$pid, 303, $i], ['value_c'], [$val], 'data_id');
}
$val = getparam('mo_sl');
COU('data', ['pers', 'type', 'value_a'], [$pid, 303, 0], ['value_b'], [$val], 'data_id');


// annat
$val = getparam('st_syn_sl');
COU('data', ['pers', 'type', 'value_a'], [$pid, 300, 1], ['value_b'], [$val], 'data_id');

$val = getparam('pro_soc_sl');
COU('data', ['pers', 'type', 'value_a'], [$pid, 300, 2], ['value_b'], [$val], 'data_id');

$val = getparam('st_sto_sl');
COU('data', ['pers', 'type', 'value_a'], [$pid, 300, 3], ['value_b'], [$val], 'data_id');

$val = getparam('pro_bes_sl');
COU('data', ['pers', 'type', 'value_a'], [$pid, 300, 4], ['value_b'], [$val], 'data_id');


?>

