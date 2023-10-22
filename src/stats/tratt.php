
<html>

	<head>
		<title>
			Tratten
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

		<hr />
		<h1> Tratten </h1>


<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

class LEAD {
	public int $id;
	public string $date;
	public string $name;
	public string $email;
	public string $phone;
	public int $utskick = -1;
	public array $tratt;
	public bool $gjort = false;
}

$query = "SELECT * FROM lead";
$result = mysqli_query($emperator, $query);
$arr = [];
while ($row = mysqli_fetch_array($result)) {

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

$query = "SELECT * FROM data WHERE type=17";
$result = mysqli_query($emperator, $query);
if ($result) while ($row = mysqli_fetch_array($result))
{
	$idx = $row['value_a'];
	$var = $row['value_b'];
	if (array_key_exists($idx, $arr))
		$arr[$idx]->utskick = $var;
}

$query = "SELECT * FROM data WHERE type=56";
$result = mysqli_query($emperator, $query);
if ($result) while ($row = mysqli_fetch_array($result))
{
	$idx = $row['value_a'];
	$score = $row['value_b'];
	$lid = $row['surv'];
	if (array_key_exists($lid, $arr)) {
		$arr[$lid]->tratt[$idx] = $score;
		$arr[$lid]->gjort = true;
	}
}

$tag = getparam("tag", 0);

$start = getparam('startdate', '');
$stop = getparam('stopdate', '');
$b = false;
$e = false;

if ($start != '')
	$b = strtotime($start);
if ($stop != '')
	$e = strtotime($stop);

$hasd = $b and $e;

if ($hasd) echo "limiting on date <br>\n";

$listing = [];
foreach ($arr as $key => $val)
{
	$variant = $val->utskick;
	if ($variant <= 0) continue;
	if ($tag!=0)
		if ($variant!=$tag)
			continue;
	if ($hasd) {
		$d = strtotime($val->date);
		if ($d<$b) continue;
		if ($d>$e) continue;
	}
	if (!array_key_exists($variant, $listing)) {
		$listing[$variant]['start'] = 0;
		$listing[$variant]['tratt'] = 0;
	}
	
	$listing[$variant]['start'] += 1;
	if ($val->gjort)
		$listing[$variant]['tratt'] += 1;
}

echo "<table> <tr> <th> email # </th> <th> start m&auml;t </th> <th> gjort tratten </th> </tr> \n";

foreach ($listing as $key => $val)
{
	echo "<tr> ";
	echo " <td> " . $key . " </td> ";
	echo " <td> " . $val['start'] . " </td> ";
	echo " <td> " . $val['tratt'] . " </td> ";
	echo " </tr> \n";
}
echo " </table> \n";

echo " <br> <br> <br> <hr> <br> <br> <br> ";

echo "<table> <tr> <th> id </th> <th> variant </th> ";
for ($i=0; $i<5; ++$i)
	echo " <th> " . chr(65+$i) . " </th> ";

if ($hasd)
	echo " <th> datum </th> ";
echo " </tr> \n";
foreach ($arr as $key => $val)
{
	if (!$val->gjort) continue;
	$variant = $val->utskick;
	if ($variant <= 0) continue;
	if ($tag!=0)
		if ($variant!=$tag)
			continue;
	if ($hasd) {
		$d = strtotime($val->date);
		if ($d<$b) continue;
		if ($d>$e) continue;
	}
	echo " <tr> ";
	echo " <td> " . $val->id . " </td> ";
	echo " <td> " . $variant . " </td> ";
	for ($i=1; $i<=5; ++$i) {
		echo " <td> " . $val->tratt[$i] . " </td> ";
	}
	if ($hasd)
		echo " <td> " . $val->date . " </td> ";	
	echo " </tr> \n";
}
echo " </table> \n";

?>

		<br /> <hr />

	</div> </body>
</html>

