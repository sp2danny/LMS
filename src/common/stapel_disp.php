
<!-- inlude stapel_disp.php -->

<?php

class DP3 {
    public $name = 'Name';
    public $vals = [];
};

function all2str($survs)
{
	$frst = true;
	$str = "";
	foreach ($survs as $key => $val)
	{
		if (!$frst) $str .= ", ";
		$frst = false;
		$str .= $key;
		$str .= "(";
		$f = true;
		foreach ($val as $v) {
			if (!$f) $str .= ",";
			$f = false;
			$str .= $v;
		}
		$str .= ")";
	}
	return $str;
}

function collect_stapel_all($pid)
{
	global $emperator;
		
	$survs = [];

	$query = "SELECT * FROM surv WHERE type=8 AND pers='" .$pid . "'";
	$res = mysqli_query($emperator, $query);
	if ($res) while($row = mysqli_fetch_array($res))
	{
		$sid = $row['surv_id'];
		$name = $row['name'];
		$query2  = "SELECT * FROM data WHERE pers='" .$pid . "'" . " AND type=8" ;
		$query2 .= " AND surv='" . $sid . "'";
		$res2 = mysqli_query($emperator, $query2);
		if ($res2) while($row2 = mysqli_fetch_array($res2)) {
			$val = $row2['value_a'];
			$survs[$name][$row['seq']] = $val;
		}
		ksort($survs[$name]);
	}

	return $survs;
}


function collect_stapel($pid, $args)
{
	global $emperator;
	
	$m_strt = $args[1];
	$m_stop = $args[2];

	$n = count($args);
	$dps = [];
	for ($i=3; $i<$n; ++$i) {
		$dp = new DP3;
		$dp->name = $args[$i];
		$dps[] = $dp;
	}

	$n = count($dps);
	for ($i=0; $i<$n; ++$i) {
		for ($m=$m_strt; $m<=$m_stop; ++$m) {
			$query = "SELECT * FROM surv WHERE type=8 AND pers='" .$pid;
			$query .= "' AND name='" . $dps[$i]->name . "' AND seq=" . $m;
			$sid = 0;
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
					$dps[$i]->vals[$m] = $val;
				}
			}
		}
	}

	return $dps;
}

function display_stapel($to, $data, $args, $num=1)
{
	global $emperator;
	
	$title  = $args[0];
	$m_strt = $args[1];
	$m_stop = $args[2];

	$dps = collect_stapel($data->pid, $args);
	$n = count($dps);

	$oksf = true;
	$str = "    ['" . $title . "'";
	for ($i=0; $i<$n; ++$i) {
		$str .= ", '" . $dps[$i]->name . "'";
	}
	$str .= "],\n";
	for ($m=$m_strt; $m<=$m_stop; ++$m) {
		$str .= "      ['" . $m . "'";
		for ($i=0; $i<$n; ++$i) {
			if (!array_key_exists($i, $dps)) { $oksf = false; break; }
			if (!property_exists($dps[$i], 'vals')) { $oksf = false; break; }
			if (!array_key_exists($m, $dps[$i]->vals)) { $oksf = false; break; }
			$str .= ", " . $dps[$i]->vals[$m];
		}
		if (!$oksf) break;
		$str .= "]";
		if ($m < $m_stop) $str .= ",";
		$str .= "\n";
	}
	$str .= "    ]);\n";

	if ($oksf) {

		$to->startTag('script');
		$to->regLine("google.charts.load('current', {'packages':['bar']});");
		$to->regLine("google.charts.setOnLoadCallback(drawChart_bar_" . $num . ");");
		$to->regLine("function drawChart_bar_" . $num . "() {");
		$to->regLine("  var data = google.visualization.arrayToDataTable([");
		$to->regLine($str);
		$to->regLine('  var options = {');
        $to->regLine('    title: "' . $title . '",');
        $to->regLine('    width: 150,');
        $to->regLine('    legend: { position: "none" },');
        $to->regLine('    chart: { title: "' . "" . '",'); // $title
        $to->regLine('             subtitle: "" },');
        $to->regLine('    bars: "vertical", // Required for Material Bar Charts.');
        $to->regLine('    axes: {');
        $to->regLine('      x: {');
        $to->regLine('        0: { side: "top", label: "' . $title . '"} // Top x-axis.');
        $to->regLine('      }');
        $to->regLine('    },');
        $to->regLine('    bar: { groupWidth: "90%" }');
        $to->regLine('  };');
        $to->regLine('  var chart = new google.charts.Bar(document.getElementById("bar_chart_' . $num . '"));');
        $to->regLine('  chart.draw(data, options);');

		$to->regLine("}");
		$to->stopTag("script");
		$to->regLine('<div id="bar_chart_' . $num . '" style="width: 150px; height: 250px"></div>');

	} else {
		$to->regLine(' <div> &lt; Data Missing &gt; </div> ');
	}

	return true;
}

function display_stapel_survs($to, $args, $survs, $num=1)
{
	$title  = $args[0];
	$data   = $args[1];
	
	if (!array_key_exists($data, $survs))
	{
		$to->regLine("no data");
		return 0;
	}

	$arr = $survs[$data];

	$n = count($arr);

	$str = "\n    [['" . $title . "'";
	//for ($i=0; $i<$n; ++$i) {
		$str .= ", '" . $title . "'";
	//}
	$str .= "],\n";
	$m = 1;
	$min = $max = 0;
	foreach ($arr as $val) {
		if ($m==1) {
			$min=$max=$val;
		} else {
			if ($val < $min) $min = $val;
			if ($val > $max) $max = $val;
		}
		$str .= "      ['" . $m++ . "'";
		$str .= ", " . $val;
		$str .= "]";
		if ($m <= $n) $str .= ",";
		$str .= "\n";
	}
	$str .= "    ]);\n";

	$title .= " +" . ($max-$min);

	$to->startTag('script');
	$to->regLine("google.charts.load('current', {'packages':['bar']});");
	$to->regLine("google.charts.setOnLoadCallback(drawChart_bar_" . $num . ");");
	$to->regLine("function drawChart_bar_" . $num . "() {");
	$to->regLine("  var data = google.visualization.arrayToDataTable(");
	$to->regLine($str);
	$to->regLine('  var options = {');
	$to->regLine('    title: "' . $title . '",');
	$to->regLine('    width: 150,');
	$to->regLine('    legend: { position: "none" },');
	$to->regLine('    chart: { title: "' . "" . '",'); // $title
	$to->regLine('             subtitle: "" },');
	$to->regLine('    bars: "vertical", // Required for Material Bar Charts.');
	$to->regLine('    axes: {');
	$to->regLine('      x: {');
	$to->regLine('        0: { side: "top", label: "' . $title . '"} // Top x-axis.');
	$to->regLine('      }');
	$to->regLine('    },');
	$to->regLine('    bar: { groupWidth: "90%" }');
	$to->regLine('  };');
	$to->regLine('  var chart = new google.charts.Bar(document.getElementById("bar_chart_' . $num . '"));');
	$to->regLine('  chart.draw(data, options);');

	$to->regLine("}");
	$to->stopTag("script");
	$to->regLine('<div id="bar_chart_' . $num . '" style="width: 150px; height: 250px"></div>');

	return ($max-$min);
}


?>
