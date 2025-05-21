
<?php

include_once 'common.php';
include_once 'connect.php';

debug_log("save_utv " . allparam());

$pid = getparam('for');
$byid = getparam('by');

$ddd = false;

// PER
COU('data', ['pers', 'type', 'value_b'],  [$pid, 321, $byid], ['value_a'], [getparam('per_1')] , "data_id", $ddd );
COU('data', ['pers', 'type', 'value_b'],  [$pid, 322, $byid], ['value_a'], [getparam('per_2')] , "data_id", $ddd );
COU('data', ['pers', 'type', 'value_b'],  [$pid, 323, $byid], ['value_a'], [getparam('per_3')] , "data_id", $ddd );

// ATO
COU('data', ['pers', 'type', 'value_b'],  [$pid, 324, $byid], ['value_a'], [getparam('ato_1')] , "data_id", $ddd );
COU('data', ['pers', 'type', 'value_b'],  [$pid, 325, $byid], ['value_a'], [getparam('ato_2')] , "data_id", $ddd );
COU('data', ['pers', 'type', 'value_b'],  [$pid, 326, $byid], ['value_a'], [getparam('ato_3')] , "data_id", $ddd );

// MMG
COU('data', ['pers', 'type', 'value_b'],  [$pid, 327, $byid], ['value_a'], [getparam('mmg_1')] , "data_id", $ddd );
COU('data', ['pers', 'type', 'value_b'],  [$pid, 328, $byid], ['value_a'], [getparam('mmg_2')] , "data_id", $ddd );
COU('data', ['pers', 'type', 'value_b'],  [$pid, 329, $byid], ['value_a'], [getparam('mmg_3')] , "data_id", $ddd );

?>

