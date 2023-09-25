
<?php

include "getparam.php";
include "db.php";

$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, " . getparam("id") . ", 'push')";

$res = mysqli_query( $emperator, $query );

$order_id = getparam('order_id');
$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, " . $order_id . ", 'push_id')";
$res = mysqli_query( $emperator, $query );


?>

