
<!-- inlude tq.php -->

<?php

function tq_start($to, $data, $args)
{

	$to->regLine("<br> " . $args[0] . " <br><hr>");

	$to->startTag("form", "id='gap' action='../common/tq_post.php'");

	$to->scTag("input", "type='hidden' id='pnr'      name='pnr'      value='" . $data->pnr . "'");

	return true;
}

function tq_query($to, $data, $args)
{
	$to->startTag("label","for='tq-".$args[0]."'");
	$to->regLine($args[1] . " <br>");
	$to->stopTag("label");
	$to->scTag("input", "type='text' id='tq-".$args[0]."' name='tq-".$args[0]."' value=''");
	$to->regLine("<br><br>");
	return true;
}

function tq_stop($to, $data, $args)
{
	$to->scTag("input", "type='submit' value='Klar'");

	$to->stopTag("form");

	$to->regLine("<br><hr>");

	return true;
}

