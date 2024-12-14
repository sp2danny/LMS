
<!-- include egenskaper.php -->

<?php

include_once 'head.php';
include_once 'roundup.php';
include_once 'util.php';
include_once 'common_php.php';
include_once 'connect.php';
include_once 'discdisplay.php';

function index()
{
	echo "</head><body>\n";
	
	global $emperator;
	
	$pnr = getparam("pnr", "0");
	$pid = getparam("pid", "0");

	if ($pnr != 0) {
		$query = "SELECT * FROM pers WHERE pnr='$pnr';";
		$res = mysqli_query($emperator, $query);
		if ($res)
			$prow = mysqli_fetch_array($res);
		$pid = $prow['pers_id'];
	}

	$disc = false;

	$query = "SELECT * FROM data WHERE pers='$pid' AND type='6';";
	$res = mysqli_query($emperator, $query);
	if ($res) {
		if ($row = mysqli_fetch_array($res)) {
			$disc = [];
			$disc['LR'] = $row['value_a'];
			$disc['UD'] = $row['value_b'];
		}
	}

	echo "<table><tr><td>\n";

	if ($disc)
	{
		echo disc_draw($disc['LR'], $disc['UD']);
	}
	
	echo "</td><td> bla bla </td></tr></table>\n";



}




index();

?>

</body>
</html>
