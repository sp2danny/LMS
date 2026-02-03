
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

?>

<script>


function sp(i)
{
	targets = [ 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99 ];
	targ_s  = [ 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85  ];
	val_e_1 =   [ 78, 25, 34, 98, 56, 33, 90, 34, 56, 67, 23, 99, 78, 56, 65, 23, 99, 78, 56 ];
	val_b_1 =   [ 55, 56, 23, 67, 76, 34, 78, 34, 99, 12, 34, 34, 78, 34, 99, 12, 34, 88, 34 ];
	short_desc_1 = [ 'Värdegrund', 'Mission', 'Utveckling', 'Disc', 'Styrkor' ];  

	val_e_2 =   [ 34, 56, 67, 23, 99, 78, 56, 65, 23, 99, 78, 56 ];
	val_b_2 =   [ 34, 78, 34, 99, 12, 34, 34, 78, 34, 99, 12, 34, 88, 34 ];
	short_desc_2 = [ 'bb', 'cc', 'dd', 'ee', 'ff', 'gg' ];  

	switch (i)
	{
		case 1:
			DrawSpider('SpiderCanvas', 5, targets, targ_s, val_e_1, val_b_1, short_desc_1, 'daniel' );
			break;
		case 2:
			DrawSpider('SpiderCanvas', 6, targets, targ_s, val_e_2, val_b_2, short_desc_2, 'blubb' );
			break;
	}
}



</script>


<?php


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
	$to->startTag('td', 'width=660px');

	echo '<canvas id="SpiderCanvas" width="550" height="630" style="border:1px solid #000000;">' ;
	echo ' Din browser st&ouml;der inte canvas </canvas> ' . "\n";

	echo " </td> <td> ";

	global $KAT;
	$KAT = 'prod' ;

	$to->stopTag('td');

	// btns 1
	$to->startTag('td', 'width=300px');
	$to->regLine('<button onclick="sp(1)" > btn 1 </button> <br> ');
	$to->regLine('<button onclick="sp(2)" > btn 2 </button> <br> ');
	$to->regLine('<button onclick="sp(3)" > btn 3 </button> <br> ');
	$to->stopTag('td');

	// btns 2
	$to->startTag('td', 'width=300px');
	$to->regLine('<button onclick="sp(4)" > btn 1 </button> <br> ');
	$to->regLine('<button onclick="sp(5)" > btn 2 </button> <br> ');
	$to->stopTag('td');


	$to->stopTag('tr');
	$to->stopTag('table');


	$to->stopTag('div'); //main
	$to->stopTag('body');


}


index();

?>

</html>
