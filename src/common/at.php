
<!DOCTYPE html>

<html>
<head>
  <title> ATO </title>
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

	include_once 'connect.php';
	include_once 'common.php';
	include_once 'main.js.php';
	include_once 'tagOut.php';
	include_once 'process_cmd.php';
	include_once 'stapel_disp.php';

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

	$survs = collect_stapel_all($pid);

	echo "<table class='plain'>";
	echo "<tr>";

	$to = new tagOut;

	{
		echo "<td class='plain'> \n";

		$args = [];
		$args[] = "Ärlig";
		$args[] = "arlig";
		display_stapel_survs($to, $args, $survs, 4);
		echo "<br> <center> " . $args[0] . " </center> ";
		echo " </td> \n";
	}

	{
		echo "<td class='plain'> \n";

		$args = [];
		$args[] = "Tillitsfull";
		$args[] = "tillit";
		display_stapel_survs($to, $args, $survs, 5);
		echo "<br> <center> " . $args[0] . " </center> ";
		echo " </td> \n";
	}

	{
		echo "<td class='plain'> \n";

		$args = [];
		$args[] = "Omdömesfull";
		$args[] = "omdome";
		display_stapel_survs($to, $args, $survs, 6);
		echo "<br> <center> " . $args[0] . " </center> ";
		echo " </td> \n";
	}

	echo "</tr>\n";
	echo "</table>\n";

	$eol = "\n";

?> 

</body></html>

