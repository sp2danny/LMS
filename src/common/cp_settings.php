
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

	echo "<br><br><br><br<br> \n";

	for ($i=1; $i<=7; ++$i)
	{

		echo "<label for='g" . $i . "t'> Graph " . $i . " type : </label> \n";

		echo "<select name='g" . $i . "t' id='g" . $i . "t'> \n";
		echo "  <option value='1'> Stapel </option> \n";
		echo "  <option value='2'> Spindel </option> \n";
		echo "  <option value='3'> Graph </option> \n";
		echo "  <option value='4'> M&auml;tare </option> \n";
		echo "</select> <br> \n";

		echo "<label for='g" . $i . "d'> Graph " . $i . " data : </label> \n";

		echo "<select name='g" . $i . "d' id='g" . $i . "d'> \n";
		$j = 1;
		foreach ($gaps as $g) {
			echo "  <option value='" . $g . "'> " . $g . " </option> \n";
			++$j;
		}
		//echo "  <option value='2'> Motiv </option> \n";
		echo "</select> <br> \n";

		echo "<br> \n";
	}


	echo "pid : " . $pid . "<br> \n";

	echo "<br><br><br><br<br> \n";

	echo "<button> Save </button> \n";
	echo "</form> \n";
	//echo "<button> Cancel </button> \n";


?>



</body>
</html>
