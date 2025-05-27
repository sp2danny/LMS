
<html>
<head> </head>
<body>

<?php

include '../site/common/common_php.php';
include '../site/common/connect.php';

$gn = getparam("gname", false);

if ($gn !== false) {
	$query = "INSERT INTO data (type, value_c, pers) VALUES (901, '$gn', 0)";
	$res = mysqli_query($emperator, $query);
	if ($res)
		echo "ok";
	else
		echo "failed, " . $query;
} else {
	echo "noop";
}

?>

<br><br>

<a href='pgl2.php'> <button> Tillbaka </button> </a>

</body>
</html>


