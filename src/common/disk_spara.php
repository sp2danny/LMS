
<?php

include "getparam.php";
include "debug.php";

$str = str_replace("\n", " ", var_export(array_merge($_GET, $_POST), true));

debug_log($str);


?>

