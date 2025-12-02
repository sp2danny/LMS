
<?php

include "getparam.php";
include "db.php";

include_once "../../site/common/debug.php";


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

$em_res = 'hittade ingen email adress';

if ($email != "")
{
    $em_to   = $email;
    $em_subj = 'Orderbekräftelse från min2excellence.se';
    $em_msg  = 'Välkommen som kund' . "\r\n";
    $em_msg .= 'Här är din inloggningslänk' . "\r\n";
    $em_msg .= 'https://www.mind2excellence.se/site/common/login.php' . "\r\n";
    $em_msg .= 'Här är din zoomlänk' . "\r\n";
    $em_msg .= 'https://www.mind2excellence.se/site/common/sendlink.php?pid=' . $id . "\r\n";
    $em_hdr  = [];
    $em_hdr['From']     = 'kundtjanst@mind2excellence.se';
    $em_hdr['Reply-To'] = 'kundtjanst@mind2excellence.se';
    $em_hdr['X-Mailer'] = 'PHP/' . phpversion();

    $ok = mail($em_to, $em_subj, $em_msg, $em_hdr);

    if ($ok)
        $em_res = "epost skickades till " . $em_to;
    else
        $em_res = "epost kunde inte skickas till " . $em_to;
}

$kid = "";
$query = "SELECT * FROM data WHERE type=55 AND value_a=" . $id;
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$kid = $row['value_c'];
}



echo <<<END

<html>
<head><title>K&ouml;p Genomf&ouml;rt</title>
<style>
	div {
		margin-top: 100px;
		margin-bottom: 100px;
		margin-right: 150px;
		margin-left: 80px;
	}
</style>
</head>
<body>
<div>
<h3> Köp genomfört</h3>
<br>

END;

echo $em_res;

echo <<<END

<br>


skapa konto:<br>
<form action="../../site/common/regpers2.php">

<label for="name"> Namn: </label> <br>
<input type="text" id="name" name="name" > <br>

<label for="pnr"> Personnummer: </label> <br>
<input type="text" id="pnr" name="pnr" > <br>

<label for="email"> Epost: </label> <br>

END;

echo '<input type="text" id="email" name="email" value="'.$email.'"  > <br>';

echo '<input type="hidden" id="kid" name="kid" value="'.$kid.'"  > ';

echo <<<END

<label for="pwd"> Lösenord: </label> <br>
<input type="password" id="pwd" name="pwd" > <br>


<input type="submit" value="Skapa Konto">

</form>
</div>
</body>
</html>

END;

?>

