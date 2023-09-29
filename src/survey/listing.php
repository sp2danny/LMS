
<html>

<head>
<title>
produkt lista
</title>
</head>

<body>

<?php

include "php/00-common.php";
include "php/00-connect.php";

class Prd
{
	public $id = 0;
	public $name = '';
	public $pdesc = '';
	public $val_a = 0;
	public $val_b = 0;
	public $val_c = '';
}

$prod = [];
$kurs = [];

$query  = "SELECT * FROM prod";

$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res)) {
	$prd = new Prd;
	$prd->id = $row['prod_id'];
	$prd->name = $row['name'];
	$prd->pdesc = $row['pdesc'];
	$prd->val_a = $row['val_a'];
	$prd->val_b = $row['val_b'];
	$prd->val_c = $row['val_c'];

	if ($row['type'] == 1)
		$prod[] = $prd;
	else
		$kurs[] = $prd;
}


?>


</body>
</html>
