<html>
<head> <title> Index </title> </head>
<body>

<?php



function getparam($key, $def = "")
{
	$ok = false;
	$res = $def;

	try {
		if (array_key_exists("$key", $_GET)) {
			$res = $_GET[$key];
			$ok = true;
		}
	} catch(Exception $e) {
	}

	if (!$ok) try {
		if (array_key_exists($key, $_POST)) {
			$res = $_POST[$key];
			$ok = true;
		}
	} catch(Exception $e) {
	}

	if ($ok)
	{
		return $res;
	} else {
		return $def;
	}
}






echo "seg = " . getparam("seg") . "<br>\n";

echo "batteri = " . getparam("batteri") . "<br>\n";

echo "person = " . getparam("person") . "<br>\n";



echo "<br>registrerat i databasen<br>";



?> 

</body>
</html>

