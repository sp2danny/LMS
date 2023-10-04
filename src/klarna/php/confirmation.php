
<?php

include "getparam.php";

$emperator = mysqli_connect("mind2excellence.se.mysql", "mind2excellence_selms", "Gra55bben", "mind2excellence_selms");

if(!$emperator) { echo " DB connect failed <br /> \n "; return; }

$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, " . getparam("id") . ", 'confirmation')";

$res = mysqli_query( $emperator, $query );

if (!$res) echo "error";

echo <<<END

<html>
<head><title>Klar</title></head>
<body>
<h3> Köp genomfört</h3>
skapa konto:<br>
<form action="../../site/common/regpers.php">

<label for="pnr"> Personnummer: </label> <br>
<input type="text" id="pnr" name="pnr" > <br>

<label for="pwd"> Lösenord: </label> <br>
<input type="text" id="pwd" name="pwd" > <br>


<input type="submit" value="Login">

</form>

</body>
</html>

END;

?>

