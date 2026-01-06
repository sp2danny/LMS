
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
	$to->stopTag('div');
	$to->stopTag('body');

}


index();

?>

</html>
