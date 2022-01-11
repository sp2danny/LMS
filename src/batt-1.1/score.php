
<!-- inlude score.php -->

<?php

include '../common/head.php';
include '../common/common.php';
include '../common/tagOut.php';
include '../common/connect.php';
include '../common/score.php';


$styr = fopen("styr.txt", "r") or die("Unable to open file!");
$local = "./";
$common = "../common/";

score($styr, $local, $common);

fclose($styr);

echo '</body></html>';

?> 



