
<!-- inlude meter_disp.php -->

<?php

function display_meter($to, $data, $args)
{
	global $emperator;

	$pnr = getparam("pnr", "0");
	$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";
	$pid = 0;
	$err = false;
	$res = mysqli_query($emperator, $query);
	if (!$res) {
		$err = 'DB Error, query person --'.$query.'--';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			$err = 'DB Error, fetch person --'.$query.'--';
		} else {
			$pid = $prow['pers_id'];
			$pnam = $prow['name'];
		}
	}

	$query = "SELECT * FROM surv WHERE type=8 AND pers='" .$pid . "' AND name='" . $args[0] . "' AND seq=" . $args[1];
	$sid = 0;
	$val = 0;
	$res = mysqli_query($emperator, $query);
	if (!$res) {
		$err = 'DB Error, query surv --'.$query.'--';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			$err = 'DB Error, fetch surv --'.$query.'--';
		} else {
			$sid = $prow['surv_id'];
		}
	}

	$query = "SELECT * FROM data WHERE pers='" .$pid . "'" . " AND type=8" .
	         " AND surv='" . $sid . "'";
	$res = mysqli_query($emperator, $query);
	if (!$res) {
		$err = 'DB Error, query data --'.$query.'--';
	} else {
		$prow = mysqli_fetch_array($res);
		if ($prow) {
			$val = $prow['value_a'];
		}
	}

	$to->regLine('<div class="container"> <div class="progress">');
	$to->regLine('<div class="progress-bar" role="progressbar" aria-valuenow="' . $val );
	$to->regLine('" aria-valuemin="0" aria-valuemax="100" style="width:' . $val . '%">' );
	$to->regLine('<span class="sr-only">' . $val . '% Complete</span>' );
	$to->regLine(' &nbsp; ' . $val . ' %' );
	$to->regLine('</div></div></div>' );

	$to->regLine('<canvas id="myCanvas_33" width="200" height="120" ></canvas>');
	$to->regLine('<script>');
	$to->regLine('  var pro = ' . $val . ';');
	$to->regLine('  var canvas = document.getElementById("myCanvas_33");');
	$to->regLine('  setProgress(pro, canvas);');
	$to->regLine('</script>');

	return true;
}


?>


