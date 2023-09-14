
<?php

echo <<<END

<html>

<header>
<title> Login </title>
</header>

<body>

<form action="dologin.php">

<label for="pnr"> Personnummer: </label> <br>
<input type="text" id="pnr" name="pnr" > <br>

<input type="submit" value="Login">

</form>

</body>
</html>

END;

?>


