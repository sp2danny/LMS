
<?php

include "getparam.php";
include "db.php";

$id = getparam("id", 0);

$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, " . $id . ", 'validation')";

$res = mysqli_query( $emperator, $query );

$str = "validation.post.keys:";
$first = true;

$_POST = json_decode(file_get_contents('php://input'), true);

foreach ($_POST as $key => $val) {
	if (!$first)
		$str .= ",";
	$str .= $key;
	$first = false;
}

$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, 0, '" . $str . "')";
$res = mysqli_query( $emperator, $query );

$str = "validation.customer.kvp:";
$first = true;

foreach ($_POST['customer'] as $key => $val) {
	if (!$first)
		$str .= ",";
	$str .= '[' . $key . ':' . $val . ']';
	$first = false;
}

$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, 0, '" . $str . "')";
$res = mysqli_query( $emperator, $query );


$email = "";
if (isset($_POST['billing_address']))
	if (isset($_POST['billing_address']['email']))
		$email = $_POST['billing_address']['email'];
if (isset($_POST['shipping_address']))
	if (isset($_POST['shipping_address']['email']))
		$email = $_POST['shipping_address']['email'];

$id = getparam('id',0);
$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (53, 0, " . $id . ", '" . $email . "')";
if (($id!=0) && ($email!=""))
	$res = mysqli_query( $emperator, $query );

$kid = $_POST['order_id'];
$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (55, 0, " . $id . ", '" . $kid . "')";
if (($id!=0) && ($kid!=""))
	$res = mysqli_query( $emperator, $query );


http_response_code(200);

?>

