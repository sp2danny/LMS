
<!DOCTYPE html>

<html>
<head>
  <title> P&Auml;R </title>
  <style>
    table.plain, th.plain, td.plain {
      border: 3px solid black;
      border-collapse: collapse;
      background-color: #fff;
      scrollbar-width: auto;
      scrollbar-height: auto;
    }

  </style>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php

	include('connect.php');
	include('common.php');

	include('main.js.php');
	include('tagOut.php');
	include('process_cmd.php');
	include('stapel_disp.php');

	echo "</head><body>";

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

	echo "<table class='plain'>";
	echo "<tr>";

	{
		echo "<td class='plain'> \n";

		$to = new tagOut;

		$data = new Data;
		$data->pnr = $pnr;
		$data->pid = $pid;

		$args = [];
		$args[] = "Ärlig";
		$args[] = "1";
		$args[] = "2";
		$args[] = "akta";
		display_stapel($to, $data, $args, 4);
		echo "<br> <center> " . $args[0] . " </center> ";
		echo " </td> \n";
	}

	{
		echo "<td class='plain'> \n";

		$to = new tagOut;

		$data = new Data;
		$data->pnr = $pnr;
		$data->pid = $pid;

		$args = [];
		$args[] = "Tillitsfull";
		$args[] = "1";
		$args[] = "2";
		$args[] = "tillit";
		display_stapel($to, $data, $args, 5);
		echo "<br> <center> " . $args[0] . " </center> ";
		echo " </td> \n";
	}

	{
		echo "<td class='plain'> \n";

		$to = new tagOut;

		$data = new Data;
		$data->pnr = $pnr;
		$data->pid = $pid;

		$args = [];
		$args[] = "Omdömesfull";
		$args[] = "1";
		$args[] = "2";
		$args[] = "omdome";
		display_stapel($to, $data, $args, 6);
		echo "<br> <center> " . $args[0] . " </center> ";
		echo " </td> \n";
	}


	echo "</tr>\n";
	echo "</table>\n";

	$eol = "\n";

?> 

</body></html>

