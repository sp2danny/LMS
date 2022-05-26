
<!-- inlude gap.php -->

<?php

function gap_query($to, $data, $args)
{

	$to->regLine("<br> g&ouml;r gapanalys " . $args[0] . "-" . $args[1] . " h&auml;r <br><hr>");

	$gaptxt = fopen("../common/gap-" . $args[0] . ".txt", "r");

	$gaplst = [];

	while (true) {
		$buffer = fgets($gaptxt, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		if (empty($buffer)) continue;
		$gaplst[] = $buffer;
	}
	fclose($gaptxt);

	$to->startTag("form", "id='gap' action='../common/gap_post.php'");

	$to->scTag("input", "type='hidden' id='pnr'      name='pnr'      value='" . $data->pnr . "'");
	$to->scTag("input", "type='hidden' id='gap-name' name='gap-name' value='" . $args[0] . "'");
	$to->scTag("input", "type='hidden' id='gap-num'  name='gap-num'  value='" . $args[1] . "'");
	$to->scTag("input", "type='hidden' id='gap-cnt'  name='gap-cnt'  value='" . count($gaplst) . "'");

	$i = 1;
	$to->startTag("table");
	foreach ($gaplst as $value) {
		$to->startTag("tr");
		$qq = "q".$i;
		$to->startTag("td");
		$to->startTag("label", "for='" . $qq . "'");
		$to->regLine($value);
		$to->stopTag("label");
		$to->stopTag("td");
		$to->startTag("td");
		$to->scTag("input", "type='range' min=0 max=100 id='".$qq."' name='".$qq."' value='0'");
		//$to->regLine("<br>");
		$to->stopTag("td");
		++$i;
		$to->stopTag("tr");
	}
	
	$to->stopTag("table");
	$to->regLine("<hr>");

	$to->scTag("input", "type='submit' value='Klar'");

	$to->stopTag("form");

	return true;
}

function gap_display($to, $data, $args)
{
	global $emperator;

	$to->regLine( "<br> visa gapanalys " . $args[0] . "-" . $args[1] . " resultat h&auml;r <br> " );

	$gaptxt = fopen("../common/gap-" . $args[0] . ".txt", "r");
	$gaplst = [];
	while (true) {
		$buffer = fgets($gaptxt, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		if (empty($buffer)) continue;
		$gaplst[] = $buffer;
	}
	fclose($gaptxt);

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
	
	$to->regLine('F&ouml;r ' . $pnam . ' <br>');

	$query = "SELECT * FROM surv WHERE pers='" .$pid . "'" . " AND type=7" .
	         " AND name='" . $args[0] . "' AND seq='" . $args[1] . "'";
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

	$to->regLine('Surv ID ' . $sid . ' <br>');

	$val_e = ' val_e = [ ';
	$val_b = ' val_b = [ ';
	$sdesc = ' sdesc = [ ';
	$trg_n = ' targets = [ ';
	$trg_s = ' targ_s  = [ ';
	
	$values = [];

	$query = "SELECT * FROM data WHERE pers='" .$pid . "'" . " AND type=7" .
	         " AND surv='" . $sid . "'";
	$res = mysqli_query($emperator, $query);
	if (!$res)
	{
		$err = 'DB Error, query data --'.$query.'--';
	} else {
		while (true) {
			$prow = mysqli_fetch_array($res);
			if (!$prow) {
				break;
			} else {
				$v_a = $prow['value_a'];
				$v_b = $prow['value_b'];
				$values[$v_a - 1] = $v_b;
			}
		}
	}
	$n = count($values);
	
	for ($i = 0; $i<$n; ++$i)
	{
		if ($i != 0) {
			$val_e .= ', ';
			$val_b .= ', ';
			$sdesc .= ', ';
			$trg_n .= ', ';
			$trg_s .= ', ';
		}
		
		//$to->regLine( $gaplst[$i] . ' : ' . $values[$i] . "<br>" );
		
		$val_e .= $values[$i] / 20.0;
		$val_b .= '0';
		$sdesc .= '"' . $gaplst[$i] . '"';
		$trg_n .= '3';
		$trg_s .= '5';
	}
		
	$val_e .= ' ]; ';
	$val_b .= ' ]; ';
	$sdesc .= ' ]; ';
	$trg_n .= ' ]; ';
	$trg_s .= ' ]; ';

	$to->regLine( "<center> <table> <tr> <td> " );
	$to->regLine( '<canvas id="SpiderCanvas" width="550" height="630" style="border:1px solid #000000;">' );
	$to->regLine( ' Din browser st&ouml;der inte canvas </canvas> ' );
	$to->regLine( " </td> <td> " );
	$to->regLine( " </td> </tr> </table> </center> " );
	$to->regLine( "<br> <div id='spdr'> </div> <br> " );

	$to->startTag('script');
	
	$to->regLine($trg_n);
	$to->regLine($trg_s);

	$to->regLine($val_e);
	$to->regLine($val_b);
	$to->regLine($sdesc);

	$to->regLine( " DrawSpider('SpiderCanvas', ".$i.", targets, targ_s, val_e, val_b, sdesc, 'GAP' ); " );
	$to->stopTag('script');

	if ($err === false)
		$to->regLine( 'All Ok.<br>' );
	else
		$to->regLine( $err );

	return true;
}

function gap_display_v1($to, $data, $args)
{
	global $emperator;

	$str = "<br> visa gapanalys ";
	$n = count($args);
	for ($i=0; $i<$n; ++$i) {
		if ($i!=0) $str .= " + ";
		$str .= $args[$i];
	}
	$str .= " resultat h&auml;r <br> ";
	$to->regLine($str);

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
	
	$to->regLine('F&ouml;r ' . $pnam . ' <br>');

	$val_e = ' val_e = [ ';
	$val_b = ' val_b = [ ';
	$sdesc = ' sdesc = [ ';
	$trg_n = ' targets = [ ';
	$trg_s = ' targ_s  = [ ';
	
	$values = [];
	$names = [];

	for ($i=0; $i<$n; ++$i) {

		$e = explode('-', $args[$i]);
		$gapname = $e[0];
		$gapnum = $e[1];

		$gaptxt = fopen("../common/gap-" . $gapname . ".txt", "r");
		$gaplst = [];
		while (true) {
			$buffer = fgets($gaptxt, 4096); // or break;
			if (!$buffer) break;
			$buffer = trim($buffer);
			if (empty($buffer)) continue;
			$gaplst[] = $buffer;
		}
		fclose($gaptxt);

		$query = "SELECT * FROM surv WHERE pers='" .$pid . "'" . " AND type=7" .
				 " AND name='" . $gapname . "' AND seq='" . $gapnum . "'";
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

		$to->regLine('Surv ID ' . $sid . ' <br>');

		$vofs = count($values);

		$query = "SELECT * FROM data WHERE pers='" .$pid . "'" . " AND type=7" .
				 " AND surv='" . $sid . "'";
		$res = mysqli_query($emperator, $query);
		if (!$res)
		{
			$err = 'DB Error, query data --'.$query.'--';
		} else {
			while (true) {
				$prow = mysqli_fetch_array($res);
				if (!$prow) {
					break;
				} else {
					$v_a = $prow['value_a'];
					$v_b = $prow['value_b'];
					$values[$vofs + $v_a - 1] = $v_b;
					$names[$vofs + $v_a - 1] = $gaplst[$v_a - 1];
				}
			}
		}
	}

	$n = count($values);
	for ($i = 0; $i<$n; ++$i)
	{
		if ($i != 0) {
			$val_e .= ', ';
			$val_b .= ', ';
			$sdesc .= ', ';
			$trg_n .= ', ';
			$trg_s .= ', ';
		}
		
		//$to->regLine( $names[$i] . ' : ' . $values[$i] . "<br>" );
		
		$val_e .= $values[$i] / 20.0;
		$val_b .= '0';
		$sdesc .= '"' . $names[$i] . '"';
		$trg_n .= '3';
		$trg_s .= '5';
	}
		
	$val_e .= ' ]; ';
	$val_b .= ' ]; ';
	$sdesc .= ' ]; ';
	$trg_n .= ' ]; ';
	$trg_s .= ' ]; ';

	$to->regLine( "<center> <table> <tr> <td> " );
	$to->regLine( '<canvas id="SpiderCanvas1" width="550" height="630" style="border:1px solid #000000;">' );
	$to->regLine( ' Din browser st&ouml;der inte canvas </canvas> ' );
	$to->regLine( " </td> <td> " );
	$to->regLine( " </td> </tr> </table> </center> " );
	$to->regLine( "<br> <div id='spdr'> </div> <br> " );

	$to->startTag('script');
	
	$to->regLine($trg_n);
	$to->regLine($trg_s);

	$to->regLine($val_e);
	$to->regLine($val_b);
	$to->regLine($sdesc);

	$to->regLine( " DrawSpider('SpiderCanvas1', ".$i.", targets, targ_s, val_e, val_b, sdesc, 'GAP' ); " );
	$to->stopTag('script');

	if ($err === false)
		$to->regLine( 'All Ok.<br>' );
	else
		$to->regLine( $err );

	return true;
}




function gap_display_v2($to, $data, $args)
{
	global $emperator;

	$str = "<br> visa gapanalys ";
	$n = count($args);
	for ($i=0; $i<$n; ++$i) {
		if ($i!=0) $str .= " + ";
		$str .= $args[$i];
	}
	$str .= " resultat h&auml;r <br> ";
	$to->regLine($str);

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
	
	$to->regLine('F&ouml;r ' . $pnam . ' <br>');

	$val_e = ' val_e = [ ';
	$val_b = ' val_b = [ ';
	$sdesc = ' sdesc = [ ';
	$trg_n = ' targets = [ ';
	$trg_s = ' targ_s  = [ ';
	
	$values = [];
	$names = [];

	for ($i=0; $i<$n; ++$i) {

		$e = explode('-', $args[$i]);
		$gapname = $e[0];
		$gapnum = $e[1];

		$gaptxt = fopen("../common/gap-" . $gapname . ".txt", "r");
		$gaplst = [];
		while (true) {
			$buffer = fgets($gaptxt, 4096); // or break;
			if (!$buffer) break;
			$buffer = trim($buffer);
			if (empty($buffer)) continue;
			$gaplst[] = $buffer;
		}
		fclose($gaptxt);

		$query = "SELECT * FROM surv WHERE pers='" .$pid . "'" . " AND type=7" .
				 " AND name='" . $gapname . "' AND seq='" . $gapnum . "'";
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

		$to->regLine('Surv ID ' . $sid . ' <br>');

		$vofs = count($values);

		$query = "SELECT * FROM data WHERE pers='" .$pid . "'" . " AND type=7" .
				 " AND surv='" . $sid . "'";
		$res = mysqli_query($emperator, $query);
		if (!$res)
		{
			$err = 'DB Error, query data --'.$query.'--';
		} else {
			while (true) {
				$prow = mysqli_fetch_array($res);
				if (!$prow) {
					break;
				} else {
					$v_a = $prow['value_a'];
					$v_b = $prow['value_b'];
					$values[$vofs + $v_a - 1] = $v_b;
					$names[$vofs + $v_a - 1] = $gaplst[$v_a - 1];
				}
			}
		}
	}

	$n = count($values);
	for ($i = 0; $i<$n; ++$i)
	{
		if ($i != 0) {
			$val_e .= ', ';
			$val_b .= ', ';
			$sdesc .= ', ';
			$trg_n .= ', ';
			$trg_s .= ', ';
		}
		
		//$to->regLine( $names[$i] . ' : ' . $values[$i] . "<br>" );
		
		$val_e .= $values[$i] / 20.0;
		$val_b .= '0';
		$sdesc .= '"' . $names[$i] . '"';
		$trg_n .= '3';
		$trg_s .= '5';
	}
		
	$val_e .= ' ]; ';
	$val_b .= ' ]; ';
	$sdesc .= ' ]; ';
	$trg_n .= ' ]; ';
	$trg_s .= ' ]; ';

	$to->regLine( "<center> <table> <tr> <td> " );
	$to->regLine( '<canvas id="SpiderCanvas2" width="550" height="630" style="border:1px solid #000000;">' );
	$to->regLine( ' Din browser st&ouml;der inte canvas </canvas> ' );
	$to->regLine( " </td> <td> " );
	$to->regLine( " </td> </tr> </table> </center> " );
	$to->regLine( "<br> <div id='spdr'> </div> <br> " );

	$to->startTag('script');
	
	$to->regLine($trg_n);
	$to->regLine($trg_s);

	$to->regLine($val_e);
	$to->regLine($val_b);
	$to->regLine($sdesc);

	$to->regLine( " DrawSpider('SpiderCanvas2', ".$i.", targets, targ_s, val_e, val_b, sdesc, 'GAP' ); " );
	$to->stopTag('script');

	if ($err === false)
		$to->regLine( 'All Ok.<br>' );
	else
		$to->regLine( $err );

	return true;
}



?>


