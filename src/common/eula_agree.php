
<!DOCTYPE html>

<html>
<head>

<?php

include_once 'connect.php';
include_once "getparam.php";

$pid = getparam("pid");

$query  = "INSERT INTO data (pers, type, value_a) ";
$query .= "VALUES ($pid, 18, 1) ";
$res = mysqli_query($emperator, $query);

$lnk = "minsida.php?pid=" . $pid;

echo "<meta http-equiv='refresh' content='0; url=$lnk' /> \n";

?>

<head>
<body></body>
</html>


