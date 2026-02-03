
<?php

$RETURNTO = 'collect';

include_once 'debug.php';
include_once 'head.php';
include_once 'common_php.php';
include_once 'tagOut.php';
include_once 'connect.php';
include_once 'main.js.php';
include_once 'util.php';

echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';

echo "\n</head>\n";

function index()
{
	$to = new tagOut;

	$to->startTag("body");

	$to->startTag('div', 'id="main" class="main"');
	
	$to->startTag('div', 'id="lbl"');
	$to->stopTag('div');
	$to->scTag('br');
	
	$to->startTag('div'); //logo
	$to->scTag('br');
	$to->scTag('img', 'width=50% src="logo.png"');
	$to->stopTag('div');
	$to->scTag('hr');

	$state = getparam("state", 0);

	$to->startTag('table');
	$to->startTag('tr');

	// spider
	$to->startTag('td', 'width=300px');
	$to->scTag('img', 'width=100% src="spdr.png"');
	$to->stopTag('td');

	// btns 1
	$to->startTag('td', 'width=300px');
	$to->regLine('<button> btn 1 </button> <br> ');
	$to->regLine('<button> btn 2 </button> <br> ');
	$to->regLine('<button> btn 3 </button> <br> ');
	$to->stopTag('td');

	// btns 2
	$to->startTag('td', 'width=300px');
	$to->regLine('<button> btn 1 </button> <br> ');
	$to->regLine('<button> btn 2 </button> <br> ');
	$to->stopTag('td');


	$to->stopTag('tr');
	$to->stopTag('table');


	$to->stopTag('div'); //main
	$to->stopTag('body');


}


index();

?>

</html>
