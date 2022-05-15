
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
		$err = 'DB Error, query person "'.$query.'"';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			$err = 'DB Error, fetch person "'.$query.'"';
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
		$err = 'DB Error, query surv "'.$query.'"';
	} else {
		$prow = mysqli_fetch_array($res);
		if (!$prow) {
			$err = 'DB Error, fetch surv "'.$query.'"';
		} else {
			$sid = $prow['surv_id'];
		}
	}

	$to->regLine('Surv ID ' . $sid . ' <br>');

	$val_e = ' val_e = [ ';
	$val_b = ' val_b = [ ';
	$sdesc = ' sdesc = [ ';

	$query = "SELECT * FROM data WHERE pers='" .$pid . "'" . " AND type=7" .
	         " AND surv='" . $sid . "'";
	$res = mysqli_query($emperator, $query);
	if (!$res)
	{
		$err = 'DB Error, query data "'.$query.'"';
	} else {
		$i = 0;
		while (true) {
			$prow = mysqli_fetch_array($res);
			if (!$prow) {
				//$err = 'DB Error, fetch data "'.$query.'"';
				break;
			} else {
				$v_a = $prow['value_a'];
				$v_b = $prow['value_b'];
				$to->regLine(  $gaplst[$i] . ' : ' . $v_a . "," . $v_b . "<br>" );
				if ($i != 0) {
					$val_e .= ', ';
					$val_b .= ', ';
					$sdesc .= ', ';
				}
				$val_e .= $v_a;
				$val_b .= $v_b;
				$sdesc .= '"' . $gaplst[$i] . '"';
			}
			++$i;
		}
	}
	$val_e .= ' ]; ';
	$val_b .= ' ]; ';
	$sdesc .= ' ]; ';

	$to->regLine( "<center> <table> <tr> <td> " );
	$to->regLine( '<canvas id="SpiderCanvas" width="550" height="630" style="border:1px solid #000000;">' );
	$to->regLine( ' Din browser st&ouml;der inte canvas </canvas> ' );
	$to->regLine( " </td> <td> " );
	$to->regLine( " </td> </tr> </table> </center> " );
	$to->regLine( "<br> <div id='spdr'> </div> <br> " );

	$to->startTag('script');
	$to->regLine( "targets = [ 3.00, 3.11, 3.22, 2.56, 2.56, 2.22, 2.78, 2.89, 3.22, 2.67, " );
	$to->regLine( "            2.33, 2.89, 3.33, 2.67, 3.22, 2.33, 2.89, 2.67, 2.22, 3.22, " );
	$to->regLine( "            2.44, 2.33, 3.11, 3.22, 2.56, 2.78 ]; " );

	$to->regLine( "targ_s  = [ 4.00 , 3.55 , 4.22 , 3.88 , 3.77 , 3.33 , 3.33 , 4.11 , 4.33 , 4.00 ," );
	$to->regLine( "            4.00 , 4.00 , 4.11 , 3.22 , 3.44 , 2.11 , 4.22 , 3.66 , 3.22 , 3.88 ," );
	$to->regLine( "            3.55 , 2.22 , 3.22 , 3.33 , 2.44 , 2.88  ]; " );

	$to->regLine($val_e);
	$to->regLine($val_b);
	$to->regLine($sdesc);

	$to->regLine( " DrawSpider('SpiderCanvas', ".$i.", targets, targ_s, val_e, val_b, sdesc, 'spindel' ); " );
	$to->stopTag('script');

	if ($err === false)
		$to->regLine( 'All Ok.<br>' );
	else
		$to->regLine( $err );

	return true;
}


?>


