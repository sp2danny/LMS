
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
include "../survey/php/00-common.php";

$styr = LoadIni("../survey/styr.txt");

class LEAD {
	public int $id;
	public string $date;
	public string $name;
	public string $email;
	public string $phone;
	public int $utskick = -1;
	public array $tratt;
	public bool $gjort = false;
	public bool $buy = false;
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

$query = "SELECT * FROM data WHERE type=54";
$result = mysqli_query($emperator, $query);
if ($result) while ($row = mysqli_fetch_array($result))
{
	$lid = $row['value_a'];
	if (array_key_exists($lid, $arr)) {
		$arr[$lid]->buy = true;
	}
}

$tag = getparam("tag", 0);

$limit = getparam("limit", 50);

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
	$d = strtotime($val->date);
	if ($hasd) {
		if ($d<$b) continue;
		if ($d>$e) continue;
	}
	if (!array_key_exists($variant, $listing)) {
		$listing[$variant]['start'] = 1;
		$listing[$variant]['tratt'] = 0;
		$listing[$variant]['first'] = $d;
		$listing[$variant]['last'] = $d;
		$listing[$variant]['over'] = [];
		$listing[$variant]['buy'] = 0;
		for ($i=0; $i<=5; ++$i)
			$listing[$variant]['over'][$i] = 0;
	} else {
		$listing[$variant]['start'] += 1;
		if ($listing[$variant]['first'] < $d)
			$listing[$variant]['first'] = $d;
		if ($listing[$variant]['last'] < $d)
			$listing[$variant]['last'] = $d;
		if ($val->gjort) {
			$anyover = false;
			for ($i=1; $i<=5; ++$i) {
				if ($val->tratt[$i] >= $limit) {
					$listing[$variant]['over'][$i] += 1;
					$anyover = true;
				}
			}
			if ($anyover)
				$listing[$variant]['over'][0] += 1;
		}
	}

	if ($val->gjort)
		$listing[$variant]['tratt'] += 1;
	if ($val->buy)
		$listing[$variant]['buy'] += 1;

}

echo "<table> <tr> <th> email # </th> <th> start m&auml;t </th> <th> första </th><th> sista <th> gjort tratten </th> ";
for ($i=1; $i<=5; ++$i)
	echo " <th> " . chr(64+$i) . " over " . $limit . " </th> ";
echo " <th> " . " any over " . $limit . " </th> ";
echo " <th> Köpt </th> ";
echo "</tr> \n";

foreach ($listing as $key => $val)
{
	echo "<tr> ";
	echo " <td> " . $key . " </td> ";
	echo " <td> " . $val['start'] . " </td> ";

	echo " <td> " . date("Y M j", $val['first']) . " </td> ";
	echo " <td> " . date("Y M j", $val['last'] ) . " </td> ";

	$per = 100.0 * $val['tratt'] / $val['start'];
	echo " <td> " . $val['tratt'] . " (" . number_format($per,1,","," ") . "%) </td> ";

	for ($i=1; $i<=5; ++$i) {
		if ($val['tratt'])
			$per = 100.0 * $val['over'][$i] / $val['tratt'];
		else
			$per = 0;
		echo " <td> " . $val['over'][$i] . " (" . number_format($per,1,","," ") . "%) </td> ";
	}

	if ($val['tratt'])
		$per = 100.0 * $val['over'][0] / $val['tratt'];
	else
		$per = 0;

	echo " <td> " . $val['over'][0] . " (" . number_format($per,1,","," ") . "%) </td> ";
	echo " <td> " . $val['buy'] . " </td> ";

	echo " </tr> \n";
}
echo " </table> \n";

echo " <br> <br> <br> <hr> <br> <br> <br> ";

echo "<table>";
echo " <tr> ";
echo " <th> variant </th> ";
echo " <th> nedladdningar </th> ";
echo " </tr> \n";
$query = "SELECT * FROM data WHERE type=57";
$result = mysqli_query($emperator, $query);
if ($result) while ($row = mysqli_fetch_array($result))
{
	echo " <tr> ";
	echo " <td> " . $row['value_b'] . " </td> ";
	echo " <td> " . $row['value_a'] . " </td> ";
	echo " </tr> \n";
}
echo "</table>\n";

echo " <br> <br> <br> <hr> <br> <br> <br> ";

echo "<table>";
for ($i=1; $i<=5; ++$i) {
	echo " <tr> ";
	echo " <td> " . chr(64+$i) . " </td> ";
	echo " <td> " . $styr['querys']["kat.$i.name"] . " </td> ";
	echo " </tr> ";
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

