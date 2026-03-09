
<?php

include_once "getparam.php";
include_once "debug.php";
include_once "connect.php";
include_once "common_php.php";

function grp_sk($for, $by, $id, $val)
{
	COU('data', ['pers', 'type', 'value_b'], [$for, $id, $by], ['value_a'], [$val], 'data_id');

}

$param = array_merge($_GET, $_POST);

//$str = str_replace("\n", " ", var_export($param, true));
//debug_log($str);

grp_sk($param['for'], $param['by'], 311, $param['LR']);

grp_sk($param['for'], $param['by'], 312, $param['UD']);



?>

