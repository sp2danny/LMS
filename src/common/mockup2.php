
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

function clearall()
{
	for (i=1; i<=3; ++i) {
		for (j=1; j<=6; ++j) {
			obj = document.getElementById("pl" + i.toString() + j.toString());
			obj.src = "emp.png" ;
		}
	}

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

	clearall();

	obj = document.getElementById("pl1" + i.toString());
	ss = "gp2.png";
	obj.src = ss ;

	setSpiderColors("#070", "#0f0");

	mhd = document.getElementById("mainhdr");

	switch (i)
	{
		case 1:
			mhd.innerHTML = "Värdegrund";
			DrawSpider('SpiderCanvas', 5, targets, targ_s, val_e_1, val_b_1, short_desc_1, 'daniel' );
			PopLst(short_desc_1, 5);
			break;
		case 2:
			mhd.innerHTML = "PÄR";
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

function tx(i)
{
	clearall();

	obj = document.getElementById("pl15");
	ss = "gp2.png";
	obj.src = ss ;

	canvas = document.getElementById("SpiderCanvas");
	var ctx=canvas.getContext("2d");
	ctx.fillStyle="#fff";

	ctx.fillRect(0,0,SZ,SZ); 
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

<style>

	.bhb {
		text-align: center;
		font-size: 24px;
		font-weight: bold;
	}
	.bhs {
		text-align: center;
		font-size: 11px;
	}

</style>

<title> Mockup </title>

<?php

function outBtn( $to, $blbl, $onclck, $ttl )
{
	$to->startTag('tr');
	$to->startTag('td');
	$to->regLine("<img id='$blbl' src='emp.png' />");
	$to->stopTag('td');
	$to->startTag('td');
	if ($ttl) {
		$to->startTag("button", "style='width:150px;' onclick='$onclck'");
		$to->regLine($ttl);
		$to->stopTag('button');
	}
	$to->stopTag('td');
	$to->stopTag('tr');
}

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

	$to->regLine(" <div id='mainhdr' style='text-align:center;' > Personlighet (DISC) </div> ");

	$to->regLine( '<canvas id="SpiderCanvas" width="450" height="450" style="border:1px solid #000000;">' );
	$to->regLine( ' Din browser st&ouml;der inte canvas </canvas> ' );

	$to->startTag('script');
	$to->regLine(' rita_disc( document.getElementById("SpiderCanvas"), document.getElementById("Disc2"), 450, -2,3); ');
	$to->regLine(' rita_more( document.getElementById("SpiderCanvas"), 450, [{x:3,y:-3}, {x:6,y:8}] ); ');
	$to->stopTag('script');

	$to->stopTag('td');

	// btns 1
	$to->startTag('td', 'class="bbox" width=300px height=200px');
	$to->startTag('table');
	$to->startTag('tr');
	$to->regLine('<td> </td>');
	$to->regLine('<td> <div class="bhb"> Mitt Jag </div> </td> ');
	$to->stopTag('tr');

	$to->startTag('tr');
	$to->regLine('<td> </td>');
	$to->regLine('<td> <div class="bhs"> &nbsp; </div> </td> ');
	$to->stopTag('tr');

	outBtn($to, "pl11", "sp(1)", "Värdegrund");
	outBtn($to, "pl12", "sp(2)", "PÄR (omtyckt)");
	outBtn($to, "pl13", "sp(3)", "ÄTO (klokskap)");
	outBtn($to, "pl14", "sp(4)", "MMG (mästarklass)");
	outBtn($to, "pl15", "tx(1)", "Min Fysik");
	outBtn($to, "pl16", "", false);
	$to->stopTag('table');

	$to->stopTag('td');

	// btns 2

	$to->startTag('td', 'class="bbox" width=300px height=200px');
	$to->startTag('table');
	$to->startTag('tr');
	$to->regLine('<td> </td>');
	$to->regLine('<td> <div class="bhb"> Min Väg </div> </td> ');
	$to->stopTag('tr');

	$to->startTag('tr');
	$to->regLine('<td> </td>');
	$to->regLine('<td> <div class="bhs"> &nbsp; </div> </td> ');
	$to->stopTag('tr');

	outBtn($to, "pl21", "st(1)", "Mål");
	outBtn($to, "pl22", "st(2)", "Stress");
	outBtn($to, "pl23", "st(3)", "Kommunikation");
	outBtn($to, "pl24", "st(4)", "Motivation");
	outBtn($to, "pl25", "st(5)", "Samarbete");
	outBtn($to, "pl26", "", false);
	$to->stopTag('table');

	$to->stopTag('td');


	// b3

	$to->startTag('td', 'class="bbox" width=300px height=200px');
	$to->startTag('table');
	$to->startTag('tr');
	$to->regLine('<td> </td>');
	$to->regLine('<td> <div class="bhb"> Åtgärdsplan </div> </td> ');
	$to->stopTag('tr');

	$to->startTag('tr');
	$to->regLine('<td> </td>');
	$to->regLine('<td> <div class="bhs"> Nå ditt sanna jag </div> </td> ');
	$to->stopTag('tr');

	outBtn($to, "pl31", "sp(0)", "Steg 1");
	outBtn($to, "pl32", "sp(0)", "Steg 2");
	outBtn($to, "pl33", "sp(0)", "Steg 3");
	outBtn($to, "pl34", "", false);
	outBtn($to, "pl35", "", false);
	outBtn($to, "pl36", "", false);
	$to->stopTag('table');

	$to->stopTag('td');

	$to->stopTag('tr');
	$to->startTag('tr');

	$to->startTag('td', '');
	$to->startTag('div', 'id="lstf"');
	$to->regLine('&#128994; Egenskattning <br> &#128993; Gruppskattning ');
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
