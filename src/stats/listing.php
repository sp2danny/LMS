

<html>

	<head>
		<title>
			Leads Lista
		</title>

		<style>
			table {
				border: 2px solid #000;
			}

			td {
				border: 1px solid #000;
				padding-top: 2px;
				padding-bottom: 2px;
				padding-right: 7px;
				padding-left: 7px;
			}

			th {
				background-color: #444;
				color: #eee;
				padding-top: 2px;
				padding-bottom: 2px;
				padding-right: 7px;
				padding-left: 7px;
			}

			body {
				margin-top: 50px;
				margin-bottom: 50px;
				margin-right: 150px;
				margin-left: 80px;
			}
		</style>

	</head>

	<body> <div>

		<br />
		<h1> Leads Lista </h1>
		<br />


<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

// object|resource|array|string|float|int|bool|null

class LEAD {
	public int $id;
	public string $date;
	public string $name;
	public string $email;
	public string $phone;
	public int $utskick = -1;
	public array $tratt;
}

$leads = [];

$query = "SELECT * FROM lead";
$result = mysqli_query($emperator, $query);
$arr = [];
if ($result) while ($row = mysqli_fetch_array($result))
{
	$lead = new LEAD;
	$idx = $row['lead_id'];
	$lead->id = $idx;
	$lead->date = $row['date'];
	$lead->name = $row['name'];
	$lead->email = $row['email'];
	$lead->phone = $row['phone'];
	$lead->tratt = [];
	$arr[$idx] = $lead;
}

//	utskick     17           lead-id      variant

$query = "SELECT * FROM data WHERE type=17";
$result = mysqli_query($emperator, $query);
if ($result) while ($row = mysqli_fetch_array($result))
{
	$idx = $row['value_a'];
	$var = $row['value_b'];
	if (array_key_exists($idx, $arr))
		$arr[$idx]->utskick = $var;
}

//function set_suv_val($i, $val, $lid)
//{
//	global $emperator;
//	$query = "INSERT INTO data (pers, type, value_a, value_b, surv) "
//		. "VALUES ('0', '56', '" . $i . "', '" . $val . "', '" . $lid . "');";
//
//	$res = mysqli_query( $emperator, $query );
//	return boolval($res);
//}

$query = "SELECT * FROM data WHERE type=56";
$result = mysqli_query($emperator, $query);
if ($result) while ($row = mysqli_fetch_array($result))
{
	$idx = $row['value_a'];
	$score = $row['value_b'];
	$lid = $row['surv'];
	if (array_key_exists($lid, $arr))
		$arr[$lid]->tratt[$idx] = $score;
}

echo "\t\t<hr /> \n";
foreach($arr as $key=>$val)
{
	echo "\t\tid-nummer : " . $val->id . " (" . $val->date . ") <br /> \n";
	echo "\t\tn-e-p : " . $val->name . " " . $val->email . " " . $val->phone . " <br /> \n";
	echo "\t\tvariant : " . $val->utskick . " <br /> \n";
	echo "\t\ttratt :";
	foreach($val->tratt as $k=>$v) {
		echo " " . $v;
	}
	echo " <br /> \n";
	echo "\t\t<hr /> \n";
}

?>



	</div> </body>
</html>

