
<?php

include "../site/common/connect.php";

function add($coll, $itm)
{
	foreach ($coll as $elem) {
		if ($itm == $elem) return $coll;
	}
	$coll[] = $itm;
	return $coll;
}

function printgroups($tbs)
{
	global $emperator;

	$grp = [];

	$query = "SELECT * FROM pers";
	$res = mysqli_query($emperator, $query);
	if ($res) while ($row = mysqli_fetch_array($res))
	{
		$g = $row['grupp'];
		if ($g == "") continue;
		if ($g == "null") continue;
		if ($g == "debug") continue;
		$grp = add($grp, $g);
	}

	echo "\n";
	foreach ($grp as $g)
	{
		for ($t=1; $t<=$tbs; ++$t) echo "\t";
		echo "<option value='" . $g . "'> Grupp " . $g . " </option>\n";
	}
	echo "\n";
}

?>

<!DOCTYPE html>

<html>
	<head>  

		<title>Group and Survey</title>

		<link rel="stylesheet" href="../site/common/main-v03.css">
		<link rel="icon" href="../site/common/favicon.ico">

		<script>

			async function replaceDiv() {
				dd = document.getElementById('grp');
				rd = document.getElementById('replacerDiv');

				doc = "surv_b.php?grp=" + dd.value;

				let waitobject = await fetch(doc);
				let txt = await waitobject.text();
				rd.innerHTML = txt;
			}

			function replaceDiv2() {
				dd = document.getElementById('PersSel');
				rd = document.getElementById('replacerDiv2');
				rd.innerHTML = dd.value;
			}


		</script>

		<style>

			.ra {
				/* margin-left: auto; */
				margin-right: 50;
				display: flex;
				justify-content: flex-end;
			}
			.main {
				margin-left: 160px;
				margin-right: 160px;
			}
			body {
				margin-bottom: 80px;
			}

		</style>

	</head>

	<body>
		<div class="main" >
			<br /> 
			<img width=50%  src="../site/common/logo.png" /> <br />
			<div>
				<br /> <br />
				<div class="ra">
					<label for="grp"> V&auml;lj Grupp: &nbsp; </label>
					<select name="grp" id="grp">
						<option disabled selected value> -- v&auml;lj grupp -- </option>
						<?php printgroups(6); ?>
					</select>
					&nbsp; &nbsp; &nbsp; <button onclick="replaceDiv()"> Visa </button> 
				</div>

			</div>
			<hr />
			<div id="replacerDiv">
			</div>
		</div>
	</body>

</html>

