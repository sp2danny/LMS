
<?php

include "getparam.php";
include "db.php";

//$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, " . getparam("id") . ", 'confirmation')";
//
//$res = mysqli_query( $emperator, $query );
//
//if (!$res) echo "error";
//
//$str = "confirmation.post.keys:";
//$first = true;
//
//$fgc = file_get_contents('php://input');
//
//$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, 0, '" . 'fgc:'.convert_uuencode($fgc) . "')";
//$res = mysqli_query( $emperator, $query );
//
//$_POST = json_decode($fgc, true);
//
//if (is_array($_POST)) foreach ($_POST as $key => $val) {
//	if (!$first)
//		$str .= ",";
//	$str .= $key;
//	$first = false;
//}
//
//$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, 0, '" . $str . "')";
//$res = mysqli_query( $emperator, $query );
//
//$email = "";
//if (isset($_POST['billing_address']))
//	if (isset($_POST['billing_address']['email']))
//		$email = $_POST['billing_address']['email'];
//if (isset($_POST['shipping_address']))
//	if (isset($_POST['shipping_address']['email']))
//		$email = $_POST['shipping_address']['email'];
//
//$id = getparam('id',0);
//$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (53, 0, " . $id . ", '" . $email . "')";
//if (($id!=0) && ($email!=""))
//	$res = mysqli_query( $emperator, $query );
//


$id = getparam('id', 0);
$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (54, 0, " . $id . ", 'payed')";
$res = mysqli_query( $emperator, $query );

$query = "UPDATE data SET value_a = value_a-1 WHERE type=50 AND pers=0";
$res = mysqli_query( $emperator, $query );

$email = "";
$query = "SELECT * FROM data WHERE type=53 AND value_a=" . $id;
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$email = $row['value_c'];
}

$kid = "";
$query = "SELECT * FROM data WHERE type=55 AND value_a=" . $id;
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$kid = $row['value_c'];
}



echo <<<END

<html>
<head><title>Klar</title></head>
<body>
<h3> Köp genomfört</h3>
skapa konto:<br>
<form action="../../site/common/regpers.php">

<label for="pnr"> Personnummer: </label> <br>
<input type="text" id="pnr" name="pnr" > <br>

<label for="email"> Epost: </label> <br>

END;

echo '<input type="text" id="email" name="email" value="'.$email.'"  > <br>';

echo '<input type="hidden" id="kid" name="kid" value="'.$kid.'"  > ';

echo <<<END

<label for="pwd"> Lösenord: </label> <br>
<input type="text" id="pwd" name="pwd" > <br>


<input type="submit" value="Login">

</form>

</body>
</html>

END;

?>

