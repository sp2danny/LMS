
<!DOCTYPE html>

<html>
<head>
  <title> Cockpit </title>
  <style>
    table.plain, th.plain, td.plain {
      border: 1px solid black;
      border-collapse: collapse;
    }
  </style>
</head>
<body>

<?php

	include('connect.php');
	include('common.php');

	//include('gap.php');
	include('main.js.php');
	include('tagOut.php');
	include('process_cmd.php');

	$pnr = getparam("pnr", "0");
	$pid = getparam("pid", "0");

	if ($pnr!=0)
		$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";
	if ($pid!=0)
		$query = "SELECT * FROM pers WHERE pers_id='" .$pid . "'";

	$res = mysqli_query($emperator, $query);
	$pid = 0;
	$name = '';

	if (!$res)
	{
		echo 'DB Error';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			echo 'DB Error';
		} else {
			$pnr = $prow['pnr'];
			$pid = $prow['pers_id'];
			$name = $prow['name'];
		}
	}

	$data_tbl = [];

	function GetQ($pid, $a) {
		global $emperator;
		$obj = new stdClass();

		$query = "SELECT * FROM data WHERE type=9 AND pers='" . $pid . "' AND value_a='" . $a . "'";

		$res = mysqli_query( $emperator, $query );
		if ($res) {
			$row = mysqli_fetch_array($res);
			if ($row) {
				$data_id = $row['data_id'];
				$obj->type = $row['value_b'];
				$obj->source = $row['value_c'];
				return $obj;
			}
		}
		return false;
	}

	$pid = getparam("pid", 0);
	for ($i=1; $i<=7; ++$i) {
		$obj = GetQ($pid, $i);
		$data_tbl[] = $obj;
	}


	echo "<table class='plain'>";
	echo "<tr>";
	echo " <th class='plain'> # </th>  <th class='plain'> type </th>  <th class='plain'> source </th>  <th class='plain'> data </th> ";
	echo "</tr>\n";
	foreach($data_tbl as $key => $entry)
	{
		echo "<tr>";

		echo "<td class='plain'>" . $key . "</td>";

		echo "<td class='plain'>";
		switch ($entry->type) {
			case 1:   echo "Stapel";              break;
			case 2:   echo "Spindel";             break;
			case 3:   echo "Graph";               break;
			case 4:   echo "M&auml;tare";         break;
			default:  echo "&lt;not found&gt;";   break;
		}
		echo " </td> ";

		echo "<td class='plain'>" . $entry->source . " </td>";
		echo " </td> ";

		echo "<td class='plain'> ";

		$to = new tagOut;

		$data = new Data;
		$data->pnr = getparam("pnr", "721106");
		$data->pid = $pid;

		$args = [];
		$args[] = $entry->source;
		$args[] = "1";
		$args[] = "3";
		$args[] = $entry->source;

		display_graph($to, $data, $args, $key);

		echo " </td>";

		echo "</tr>\n";
	}
	echo "</table>\n";


?> 

</body></html>

