
<?php

include '../common/head.php';
include '../common/common.php';
include '../common/tagOut.php';
include '../common/connect.php';
include '../common/one_post.php';

$styr = fopen("styr.txt", "r") or die("Unable to open file!");
$local = "./";
$common = "../common/";

index($styr, $local, $common);

fclose($styr);

?> 

</html>
