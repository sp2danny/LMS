
<?php

include "common.php";
include "connect.php";


$lid     = getparam('lid');
$stress  = getparam('stress');
$dtype   = 30;

$query  = "INSERT INTO data (pers, type, value_a)";
$query .= "VALUES ('" . $lid . "', '" . $dtype . "', '" . $stress . "')";

$result = mysqli_query($emperator, $query);

?>


