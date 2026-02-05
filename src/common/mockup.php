
<?php

$RETURNTO = 'collect';

include_once 'debug.php';
include_once 'head.php';
include_once 'common_php.php';
include_once 'tagOut.php';
include_once 'connect.php';
include_once 'main.js.php';
include_once 'util.php';
include_once 'discdisplay.php';


echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';

?>

<script>

function PopLst(lst, nn = -1)
{
	obj = document.getElementById("lstf");
	if (nn==-1)
		n = lst.length;
	else
		n = nn;
	txt = "";
	for (i=0; i<n; ++i)
	{
		if (i!=0) txt += " <br> ";
		txt += (i+1).toString();
		txt += " : ";
		txt += lst[i];
	}
	obj.innerHTML = txt;
}

function sp(i)
{
	targets = [ 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99 ];
	targ_s  = [ 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85  ];
	val_e_1 =   [ 78, 25, 34, 98, 56, 33, 90, 34, 56, 67, 23, 99, 78, 56, 65, 23, 99, 78, 56 ];
	val_b_1 =   [ 55, 56, 23, 67, 76, 34, 78, 34, 99, 12, 34, 34, 78, 34, 99, 12, 34, 88, 34 ];
	short_desc_1 = [ 'Värdegrund', 'Mission', 'Utveckling', 'Disc', 'Styrkor', 'xx', 'xx'];  

	val_e_2 =   [ 34, 56, 67, 23, 99, 78, 56, 65, 23, 99, 78, 56 ];
	val_b_2 =   [ 34, 78, 34, 99, 12, 34, 34, 78, 34, 99, 12, 34, 88, 34 ];
	short_desc_2 = [ 'bb', 'cc', 'dd', 'ee', 'ff', 'gg' ];

	for (ii=1; ii<=7; ++ii)
	{
		obj = document.getElementById("pl" + ii.toString());
		if (i == ii)
			ss = "gp2.png";
		else
			ss = "emp.png";
		obj.src = ss ;
	}

	setSpiderColors("#070", "#0f0");

	switch (i)
	{
		case 1:
			DrawSpider('SpiderCanvas', 5, targets, targ_s, val_e_1, val_b_1, short_desc_1, 'daniel' );
			PopLst(short_desc_1, 5);
			break;
		case 2:
			DrawSpider('SpiderCanvas', 6, targets, targ_s, val_e_2, val_b_2, short_desc_2, 'blubb' );
			PopLst(short_desc_2, 6);
			break;
		case 3:
			DrawSpider('SpiderCanvas', 7, targets, targ_s, val_e_1.slice(2), val_b_1.slice(2), short_desc_1, 'daniel2' );
			PopLst(short_desc_1, 7);
			break;
		case 4:
			DrawSpider('SpiderCanvas', 6, targets, targ_s, val_e_2.slice(1), val_b_2.slice(1), short_desc_2, 'blubb' );
			PopLst(short_desc_2, 6);
			break;
		case 5:
			DrawSpider('SpiderCanvas', 5, targets, targ_s, val_e_2.slice(2), val_b_2.slice(2), short_desc_2, 'blubb' );
			PopLst(short_desc_2, 5);
			break;
		case 6:
			DrawSpider('SpiderCanvas', 6, targets, targ_s, val_e_2.slice(1), val_b_2.slice(1), short_desc_2, 'blubb' );
			PopLst(short_desc_2, 6);
			break;
		case 7:
			DrawSpider('SpiderCanvas', 5, targets, targ_s, val_e_2.slice(2), val_b_2.slice(2), short_desc_2, 'blubb' );
			PopLst(short_desc_2, 5);
			break;
	}
}

function rita_disc(canvas, bgimg, SZ, lr, ud)
{
	var ctx=canvas.getContext("2d");
	ctx.fillStyle="#fff";

	ctx.fillRect(0,0,SZ,SZ); 
	
	ctx.drawImage(bgimg, 0,0, SZ, SZ);
	
	ctx.beginPath(); 
	ctx.fillStyle="#373"; 
	ctx.strokeStyle="#000";
	pt = SZ / 50;
	ctx.arc( (SZ/2)+pt*lr , (SZ/2)+pt*ud ,9,0,2*Math.PI );
	ctx.stroke();
	ctx.fill(); 
}


function rita_more(canvas, SZ, lst)
{
	var ctx = canvas.getContext("2d");
	
	ctx.fillStyle = "#ef3";
	ctx.strokeStyle = "#000";
	pt = SZ / 50;
	sz2 = SZ / 2;

	for (const elem of lst) {
		ctx.beginPath(); 
		ctx.arc( sz2+pt*elem.x, sz2+pt*elem.y, 7, 0,2*Math.PI );
		ctx.stroke();
		ctx.fill(); 
	}
}

</script>


<?php


echo "\n</head>\n";

function index()
{
	$to = new tagOut;

	$to->startTag("body");

	$to->scTag("img", "id='Disc2' src='Disc3-3.png' hidden=true" );


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
	$to->startTag('td', 'width=450px rowspan=2');

	$to->regLine( '<canvas id="SpiderCanvas" width="450" height="450" style="border:1px solid #000000;">' );
	$to->regLine( ' Din browser st&ouml;der inte canvas </canvas> ' );

	$to->startTag('script');
	$to->regLine(' rita_disc( document.getElementById("SpiderCanvas"), document.getElementById("Disc2"), 450, -2,3); ');
	$to->regLine(' rita_more( document.getElementById("SpiderCanvas"), 450, [{x:3,y:-3}, {x:6,y:8}] ); ');
	$to->stopTag('script');

	$to->stopTag('td');

	// btns 1
	$to->startTag('td', 'class="bbox" width=300px height=200px');
	$to->regLine('<img id="pl1" src="emp.png" /> <button style="width: 150px;" onclick="sp(1)" > btn 1 </button> <br> ');
	$to->regLine('<img id="pl2" src="emp.png" /> <button style="width: 150px;" onclick="sp(2)" > btn 2 </button> <br> ');
	$to->regLine('<img id="pl3" src="emp.png" /> <button style="width: 150px;" onclick="sp(3)" > btn 3 </button> <br> ');
	$to->stopTag('td');

	// btns 2
	$to->startTag('td', 'class="bbox" width=300px height=200px');
	$to->regLine('<img id="pl4" src="emp.png" /> <button style="width: 150px;" onclick="sp(4)" > btn 4 </button> <br> ');
	$to->regLine('<img id="pl5" src="emp.png" /> <button style="width: 150px;" onclick="sp(5)" > btn 5 </button> <br> ');
	$to->stopTag('td');

	// b3
	$to->startTag('td', 'class="bbox" width=300px');
	$to->regLine('<img id="pl6" src="emp.png" /> <button style="width: 150px;" onclick="sp(6)" > btn 6 </button> <br> ');
	$to->regLine('<img id="pl7" src="emp.png" /> <button style="width: 150px;" onclick="sp(7)" > btn 7 </button> <br> ');
	$to->stopTag('td');

	$to->stopTag('tr');
	$to->startTag('tr');

	$to->startTag('td', '');
	$to->startTag('div', 'id="lstf"');
	$to->regLine('lista <br> med <br> forklaring  ');
	$to->stopTag('div');
	$to->stopTag('td');


	$to->startTag('td', 'colspan=2');
	$to->regLine('<img src="gg.png" \> <br> ');
	$to->stopTag('td');

	$to->stopTag('tr');

	$to->stopTag('table');


	$to->stopTag('div'); //main
	$to->stopTag('body');


}


index();

?>

</html>
