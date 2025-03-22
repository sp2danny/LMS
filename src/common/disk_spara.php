
<?php

include "getparam.php";
include "debug.php";

function grp_sk(fr, by, id, val)
{
  var str = "grp-sk-2.php"
  str += "?fr=" + fr;
  str += "&by=" + by;
  str += "&id=" + id;
  str += "&val=" + val;
  fetch(str);
}

$param = array_merge($_GET, $_POST);

//$str = str_replace("\n", " ", var_export($param, true));

//debug_log($str);

grp_sk($param['for'], $param['by'], 311, $param['LR']);

grp_sk($param['for'], $param['by'], 312, $param['UD']);



?>

