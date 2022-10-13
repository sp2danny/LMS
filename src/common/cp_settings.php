
<!DOCTYPE html>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../common/main-v01.css">

<html>
<head>
  <title> Settings </title>
</head>
<body>

<?php

	include('connect.php');
	include('common.php');

	$pnr = getparam("pnr", "0");
	$pid = getparam("pid", "0");

	if ($pnr!=0)
		$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";
	if ($pid!=0)
		$query = "SELECT * FROM pers WHERE pers_id='" .$pid . "'";

	$res = mysqli_query($emperator, $query);
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

	echo "<h3> Settings for " . $name . "</h3> \n";
	
	echo "<form id='cps' action='../common/cps_post.php' > \n";
	
	echo "<input type='hidden' id='pid' name='pid' value='" . $pid . "' >\n";


	$allfiles = scandir("./");
	//echo count($allfiles) . " <br> \n";
	$gaps = [];
	foreach ($allfiles as $file) {
		//echo $file . " <br> \n";
		$p = strpos($file, "gap-");
		if ($p===false) continue;
		$f = substr($file, $p+4);
		$p = strpos($f, ".txt");
		if ($p===false) continue;
		$f = substr($f, 0, $p);
		$gaps[] = ucfirst($f);
	}
	
	//print_r($gaps);

	function prnt_opt($val, $txt, $def)
	{
		if ($val == $def)
			return "<option selected='selected' value='" . $val . "'> " . $txt . " </option>";
		else
			return "<option value='" . $val . "'> " . $txt . " </option>";
	}

	echo "<br><br><br><br<br> \n";

	for ($i=1; $i<=7; ++$i)
	{
		$query = "SELECT * FROM data WHERE type=9 AND pers='" . $pid . "' AND value_a='" . $i . "'";

		$res = mysqli_query($emperator, $query);

		$val_a = 1;
		$val_b = $gaps[0];

		if ($res) {
			if ($prow = mysqli_fetch_array($res)) {
				//var_dump($prow);
				$val_a = $prow['value_b'];
				$val_b = $prow['value_c'];
			}
		}

		echo "<label for='g" . $i . "t'> Graph " . $i . " type : </label> \n";

		echo "<select name='g" . $i . "t' id='g" . $i . "t'> \n";
		echo "  " . prnt_opt('1', 'Stapel', $val_a)      . " \n";
		echo "  " . prnt_opt('2', 'Spindel', $val_a)     . " \n";
		echo "  " . prnt_opt('3', 'Graph', $val_a)       . " \n";
		echo "  " . prnt_opt('4', 'M&auml;tare', $val_a) . " \n";
		echo "</select> <br> \n";

		echo "<label for='g" . $i . "d'> Graph " . $i . " data : </label> \n";

		echo "<select name='g" . $i . "d' id='g" . $i . "d'> \n";
		$j = 1;
		foreach ($gaps as $g) {
			echo "  " . prnt_opt($g, $g, $val_b) . " \n";
			++$j;
		}
		echo "</select> <br> \n";

		echo "<br> \n";
	}

	//echo "pid : " . $pid . "<br> \n";

	echo "<br><br><br><br<br> \n";

	echo "<button> Spara </button> \n";
		
	echo "</form> \n";
	
	echo "<a href='login.php' > \n";
	echo "<button> Tillbaka </button> \n";
	echo "</a> \n";

?>


</body>
</html>
