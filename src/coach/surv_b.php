
<?php

include "../site/common/getparam.php";
include "../site/common/connect.php";

$grp = getparam("grp");

echo "data f&ouml;r grupp $grp <br>\n";

function tabulate($ao)
{
	$str = "<tr>";

	$i = array_key_first($ao);
	$fo = $ao[$i];

	$keys = [];

	foreach ($fo as $k => $v)
	{
		$str .= " <th> " . $k . " </th>";
		$keys[] = $k;
	}
	$str .= " </tr>";

	foreach ($ao as $o)
	{
		$str .= " <tr>";
		foreach ($keys as $k)
		{
			$str .= " <td>" . $o[$k] . " </td>";
		}
		$str .= " </tr>";
	}
	return $str;
}

$pers = [];

$query = "SELECT * FROM pers WHERE grupp='$grp'";
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	$po = [];
	$pid = $row['pers_id'];
	$po['pid'] = $pid;
	$po['name'] = $row['name'];
	$pers[$pid] = $po;
}

if (count($pers)) {
	echo "<hr> <table> \n";
	echo tabulate($pers) . "\n";
	echo "</table> \n";
}

$srvs = [];

foreach ($pers as $byo)
{
	$by = $byo['pid'];
	foreach ($pers as $fro)
	{
		$fr = $fro['pid'];
		$query = "SELECT * FROM surv WHERE type='209' AND pers=$fr AND seq=$by";
		$res = mysqli_query($emperator, $query);
		if ($res) while ($row = mysqli_fetch_array($res))
		{
			$s = [];
			$sid = $row['surv_id'];
			$s['id'] = $sid;
			$s['by'] = $by;
			$s['for'] = $fr;
			$srvs[$sid] = $s;
		}
	}
}

if (count($srvs)) {
	echo "<hr> <table> \n";
	echo tabulate($srvs) . "\n";
	echo "</table> \n";
}

$dps = [];

foreach ($srvs as $ss)
{
	$sid = $ss['id'];
	$query = "SELECT * FROM data WHERE type='209' AND surv=$sid";
	$res = mysqli_query($emperator, $query);
	if ($res) while ($row = mysqli_fetch_array($res))
	{
		$dp = [];
		$dp['surv']  = $sid;
		$dp['for']   = $pers[$srvs[$sid]['for']]['name'];
		$dp['by']    = $pers[$srvs[$sid]['by'] ]['name'];
		$dp['type']  = $row['value_a'];
		$dp['value'] = $row['value_b'];
		$dp['when']  = $row['date'];
		$dps[] = $dp;
	}
}

if (count($dps)) {
	echo "<hr> <table> \n";
	echo tabulate($dps) . "\n";
	echo "</table> \n";
}

?>

<br /> <hr />
<div class="ra">
	<label for="PersSel"> V&auml;lj person: &nbsp; </label>
	<select name="PersSel" id="PersSel">
		<option disabled selected value> -- v&auml;lj person -- </option>
		<?php
			foreach ($pers as $p) {
				for ($t=1; $t<=3; ++$t) echo "\t";
				echo "<option value='" . $p["pid"] . "'> " . $p['name'] . " </option>\n";
			}
		?>
	</select>
	&nbsp; &nbsp; &nbsp; <button onclick="replaceDiv2( )"> Visa </button> 
</div>

<hr>

<div id="replacerDiv2">
</div>

