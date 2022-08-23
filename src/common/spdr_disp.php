
<!-- inlude spdr_disp.php -->

<?php









class DP2 {
    public $name = 'Name';
    public $vals = [];
};

function display_spider($to, $data, $args, $num=1)
{
	global $emperator;

	// !graph Titel, 1, 3, Motivation, Balans
	$title  = $args[0];
	$m_strt = $args[1];
	$m_stop = $args[2];
	
	$n = count($args);
	$dps = [];
	for ($i=3; $i<$n; ++$i) {
		$dp = new DP2;
		$dp->name = $args[$i];
		$dps[] = $dp;
	}
	
	$nnmax = 0;
	
	$pnr = getparam("pnr", "0");
	$query = "SELECT * FROM pers WHERE pnr='" .$pnr . "'";
	$pid = 0;
	$err = false;
	$res = mysqli_query($emperator, $query);
	if (!$res)
	{
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

	$n = count($dps);

	$short_desc = [];

	for ($i=0; $i<$n; ++$i) {
		$short_desc[] = " " . ($i+1) . " ";
	}

	for ($i=0; $i<$n; ++$i) {
		for ($m=$m_strt; $m<=$m_stop; ++$m) {
			$query = "SELECT * FROM surv WHERE type=8 AND pers='" .$pid . "' AND name='" . $dps[$i]->name . "' AND seq=" . $m;
			$sid = 0;
			$res = mysqli_query($emperator, $query);
			if (!$res)
			{
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
			if (!$res)
			{
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

	foreach ($dps as $d)
	{
		$nv = count($d->vals);
		if ($nv > $nnmax)
			$nnmax = $nv;
	}
	
	$trg_b = [];
	$trg_s = [];
	$val_e = [];
	$val_b = [];
	for ($i=0; $i<$nnmax; ++$i) {
		$trg_b[] = 0;
		$trg_s[] = 0;
		$val_e[] = 0;
		$val_b[] = 0;
	}
	
	$nset = 0;
	/*function cond_set($a, $b, $c, $d, $e)
	{
		if (!array_key_exists($b, $a)) return;
		if (!array_key_exists($d, $c)) return;
		if (!array_key_exists($e, $c[$d]->vals)) return;
		++$nset;
		$a[$b] = $c[$d]->vals[$e] ;
	}*/

	if ($n >= 1)
		for ($i=0; $i<$nnmax; ++$i)
			$val_e[$i] = $dps[0]->vals[$i+$m_strt];
	if ($n >= 2)
		for ($i=0; $i<$nnmax; ++$i)
			$val_b[$i] = $dps[1]->vals[$i+$m_strt];
	if ($n >= 3)
		for ($i=0; $i<$nnmax; ++$i)
			$trg_b[$i] = $dps[2]->vals[$i+$m_strt];
	if ($n >= 4)
		for ($i=0; $i<$nnmax; ++$i)
			$trg_s[$i] = $dps[3]->vals[$i+$m_strt];



	/*{
		cond_set($val_e, $i, $dps, 0, $i);
		cond_set($val_b, $i, $dps, 1, $i);
		cond_set($trg_b, $i, $dps, 2, $i);
		cond_set($trg_s, $i, $dps, 3, $i);
	}*/
	
	$val_e_str = "  val_e = [ ";
	$val_b_str = "  val_b = [ ";
	$trg_b_str = "  trg_b = [ ";
	$trg_s_str = "  trg_s = [ ";
	for ($i=0; $i<$nnmax; ++$i) {
		if ($i != 0) {
			$val_e_str .= ", ";
			$val_b_str .= ", ";
			$trg_b_str .= ", ";
			$trg_s_str .= ", ";
		}
		$val_e_str .= $val_e[$i] / 10.0;
		$val_b_str .= $val_b[$i] / 10.0;
		$trg_b_str .= $trg_b[$i] / 10.0;
		$trg_s_str .= $trg_s[$i] / 10.0;
	}
	$val_e_str .= " ];";
	$val_b_str .= " ];";
	$trg_b_str .= " ];";
	$trg_s_str .= " ];";
	
	//var_dump($val_e);
	
	$short_str = "  short_desc = [ ";
	for ($i=0; $i<$nnmax; ++$i) {
		if ($i != 0) $short_str .= ", ";
		$short_str .= "'" . ($i+1) . "'"; // $short_desc[$i];
	}
	$short_str .= " ];";

	$to->regLine('<canvas id="spdr_cnv_' . $num . '" width="275" height="315" style="border:1px solid #000000;">' );
	$to->regLine(' Din browser st&ouml;der inte canvas </canvas> ' );
	
	$to->startTag('script');
	
	//$to->regLine( '// n-set : ' . $nset . "\n" );
	
	$to->regLine($val_e_str);
	$to->regLine($val_b_str);
	$to->regLine($trg_b_str);
	$to->regLine($trg_s_str);
	$to->regLine($short_str);
	
	$dss = "DrawSpider('spdr_cnv_" . $num . "', " . $nnmax . ", trg_b, trg_s, val_e, val_b, short_desc, 'spindel' );";
	
	$to->regLine($dss . " \n");

	$to->stopTag('script');
	
	$to->regLine('<br> <button onClick="' . $dss . '" > redraw </button> ' );


	return true;
}


?>


