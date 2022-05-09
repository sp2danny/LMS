
<!-- inlude gap.php -->

<?php

function gap_query($to, $data, $args)
{

	$to->regLine("<br> g&ouml;r gapanalys " . $args[0] . "-" . $args[1] . " h&auml;r <br>");

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

	$to->scTag("input", "type='text' id='pnr' name='pnr' value='" . $data->pnr . "'");
	$to->scTag("input", "type='text' id='gap' name='gap' value='" . $args[1] . "'");

	$i = 1;
	foreach ($gaplst as $value) {
		$qq = "q".$i;
		$to->startTag("label", "for='" . $qq . "'");
		$to->regLine($value);
		$to->stopTag("label");
		$to->scTag("input", "type='number' id='".$qq."' name='".$qq."' value='0'");
		$to->regLine("<br>");
		++$i;
	}

	$to->scTag("input", "type='submit' value='submit'");

	$to->stopTag("form");

	return true;
}

function gap_display($to, $data, $args)
{


	$to->regLine( "<br> visa gapanalys " . $args[0] . "-" . $args[1] . " resultat h&auml;r <br> " );

	return true;
}


?>


