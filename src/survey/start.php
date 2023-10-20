
<?php

include "../common/getparam.php";

echo "<html><head>";

echo '<meta http-equiv="refresh" content=';
echo '"' . "0; URL='php/02-b-lead_reg.php";
$variant = getparam("variant", 0);
if ($variant > 0)
	echo "?variant=" . $variant;
echo "'" . '"' . " />";

?>

</head>
<body></body>
</html>

