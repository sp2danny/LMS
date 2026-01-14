
<?php

$RETURNTO = 'collect';

include_once 'debug.php';
include_once 'head.php';
include_once 'common_php.php';
include_once 'tagOut.php';
include_once 'connect.php';

echo "</head>\n";

function index()
{
	$to = new tagOut;

	$to->startTag("body");

	$to->startTag('div', 'id="main" class="main"');
	
	$to->startTag('div', 'id="lbl"');
	$to->stopTag('div');
	$to->scTag('br');
	
	$to->startTag('div');
	$to->scTag('br');
	$to->scTag('img', 'width=50% src="logo.png"');

	$to->stopTag('div');


	// 1 - egenskattning

	$to->regLine('<hr>');
	$to->regLine('<h1> Egenskattning </h1>');


	$vg = ROD('data', ['pers', 'type'], [$pid, 201], 'value_a', false);
	$to->regLine("Värdegrund : $vg <br> \n");

	$ms = ROD('data', ['pers', 'type'], [$pid, 202], 'value_a', false);
	$to->regLine("MissionStatement : $ms <br> \n");

	$mot = ROD('data', ['pers', 'type'], [$pid, 302], 'value_a', false);
	$to->regLine("Motivation : $mot <br> \n");

	$sam = ROD('data', ['pers', 'type'], [$pid, 105], 'value_b', false);
	$to->regLine("Samarbete : $sam <br> \n");

	$str = ROD('data', ['pers', 'type'], [$pid, 101], 'value_b', false);
	$to->regLine("Styrkor : $str <br> \n");

	$kom = ROD('data', ['pers', 'type'], [$pid, 103], 'value_b', false);
	$to->regLine("Kommunikation : $kom <br> \n");

	$mal = ROD('data', ['pers', 'type'], [$pid, 104], 'value_a', false);
	$to->regLine("Målsättning : $kom <br> \n");



	$to->stopTag('div');
	$to->stopTag('body');



}


index();

?>

</html>
