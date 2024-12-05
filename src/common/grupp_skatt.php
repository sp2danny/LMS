
<!DOCTYPE html>

<!-- meta charset="UTF-8" --> 

<html>
<head>

	<title> Grupp Skattning </title>

<style>

* {
	font-size: 26px;	
}

p {
	font-size: 26px;
	margin-left: 15px;
}

body {
	margin-bottom: 75px;
	margin-left: 15px;
	margin-right: 260px;
	font-size: 28px;
	padding: 0px 10px;
	background-color: #ffffff;
}

.sw
{
    width: 450px;
}

</style>

</head>

<body>

<?php

include_once 'tagOut.php';
include_once 'connect.php';
include_once 'debug.php';
include_once 'getparam.php';
include_once 'stapel_disp.php';

function index()
{
	global $RETURNTO;

	debug_log("index() in $RETURNTO.php");

	global $emperator;

	$to = new tagOut;

	$to->startTag('div', 'id="main" class="main"');

	$to->regLine('<br /> <img width=50%  src="logo.png" /> <br /> <br /> <br />');
	
	$to->stopTag('div');
	
	$by  = getparam('pid');
	$for = getparam('for');

	$data = collect_stapel_all($for);
	
	$by_n = $fr_n = '--fel--';

	$query = "SELECT * FROM pers WHERE pers_id='$by';";
	$res = mysqli_query($emperator, $query);
	if ($res) if($row = mysqli_fetch_array($res))
		$by_n = $row['name'];
	
	$query = "SELECT * FROM pers WHERE pers_id='$for';";
	$res = mysqli_query($emperator, $query);
	if ($res) if($row = mysqli_fetch_array($res))
		$fr_n = $row['name'];

	$to->startTag('div');

	$to->regLine('grupp-skattning av  ' . $by_n . " <br />\n");
	$to->regLine('grupp-skattning för ' . $fr_n . " <br /><hr />\n");

	$to->startTag('form', "action='grp_skt.php'");

	$to->regLine("<input name='for' type='hidden' value='$for' />");
	$to->regLine("<input name='by' type='hidden' value='$by' />");

	$to->startTag('table');

	foreach($data as $key => $val)
	{
		$max = 0;
		foreach($val as $v)
			if ($v>$max) $max = $v;

		$to->startTag('tr');
		
		$to->startTag('td');
		$to->regLine('skattning');
		$to->stopTag('td');
		
		$to->startTag('td');
		$to->regLine($key);
		$to->stopTag('td');
		
		$to->startTag('td');
		$to->regLine("<input class='sw' min=0 max=100 step=1 value=$max list='tick-$key' name='$key' type='range' /> ");
		$to->startTag('datalist', "id='tick-$key'");
		$to->regLine("<option> 0 </option>");
		$to->regLine("<option> $max </option>");
		$to->regLine("<option> 100 </option>");
		$to->stopTag('datalist');
		$to->stopTag('td');
		
		$to->stopTag('tr');
	}

	$to->stopTag('table');

	$to->scTag('input', "type='submit'");

	$to->stopTag('form');

	$to->stopTag('div');
}

index();

?>

</body>
</html>




