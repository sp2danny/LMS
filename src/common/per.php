
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

    .prog-footer {
       position: fixed;
       left: 0;
       bottom: 0;
       width: 100%;
       background-color: white;
       color: white;
       text-align: center;
    }
  </style>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <script>
    function setProgress(pro, cnv) {
      var ctx = cnv.getContext("2d");
      ctx.fillStyle = "#F2F3F7";
      ctx.fillRect(0,0,200,200);
      ctx.strokeStyle = "#000";
      ctx.lineWidth = 12;
      ctx.beginPath();
      ctx.arc(100, 100, 75, 1 * Math.PI, 2 * Math.PI);
      ctx.stroke();
      ctx.strokeStyle = "#fff";
      ctx.lineWidth = 10;
      ctx.beginPath();
      ctx.arc(100, 100, 75, 1.01 * Math.PI, 1.99 * Math.PI);
      ctx.stroke();
      if (pro > 0) {
        ctx.strokeStyle = "#7fff7f";
        ctx.lineWidth = 10;
        ctx.beginPath();
        ctx.arc(100, 100, 75, 1.01 * Math.PI, (1.01+0.98*(pro/100.0)) * Math.PI);
        ctx.stroke();
      }
      ctx.fillStyle = "#7f7";
      ctx.lineWidth = 1;
      ctx.strokeStyle = "#000";
      ctx.font = "35px Arial";
      ctx.textAlign = "center";
      ctx.fillText( pro.toString() + " %", 100, 98);
      ctx.strokeText( pro.toString() + " %", 100, 98);
    }
  </script>

<?php

	include('connect.php');
	include('common.php');

	//include('gap.php');
	include('main.js.php');
	include('tagOut.php');
	include('process_cmd.php');
	include('spdr_disp.php');
	include('meter_disp.php');
	include('stapel_disp.php');
	include('progress.php');

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
		$args[] = "Positiv";
		$args[] = "1";
		$args[] = "3";
		$args[] = "Arlig";
		display_stapel($to, $data, $args, 1);
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
		$args[] = "Äkta";
		$args[] = "1";
		$args[] = "3";
		$args[] = "Arlig";
		display_stapel($to, $data, $args, 2);
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
		$args[] = "Relevant";
		$args[] = "1";
		$args[] = "3";
		$args[] = "Arlig";
		display_stapel($to, $data, $args, 3);
		echo "<br> <center> " . $args[0] . " </center> ";
		echo " </td> \n";
	}

	echo "</tr>\n";
	echo "</table>\n";

	$eol = "\n";

	//$snum = 3;
	//$maxseg = 7;
	//$pro = round(progress($snum, $maxseg));
	//echo '<div class="prog-footer">' . $eol;
	//echo '<div class="container"> <div class="progress">' . $eol;
	//echo '<div class="progress-bar" role="progressbar" aria-valuenow="' . $pro;
	//echo '" aria-valuemin="0" aria-valuemax="100" style="width:' . $pro . '%">' . $eol;
	//echo '<span class="sr-only">' . $pro . '% Complete</span>' . $eol;
	//echo ' &nbsp; ' . $pro . ' %' . $eol;
	//echo '</div></div></div></div>' . $eol;







?> 

</body></html>

