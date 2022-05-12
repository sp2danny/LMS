
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

	$to->scTag("input", "type='hidden' id='pnr' name='pnr' value='" . $data->pnr . "'");
	$to->scTag("input", "type='hidden' id='gap' name='gap-name' value='" . $args[0] . "'");
	$to->scTag("input", "type='hidden' id='gap' name='gap-num' value='" . $args[1] . "'");
	$to->scTag("input", "type='hidden' id='gap' name='gap-cnt' value='" . count($gaplst) . "'");

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


	$to->regLine( "<br> visa gapanalys " . $args[0] . "-" . $args[1] . " resultat h&auml;r <br> " );

	return true;
}


?>


