
<?php

$RETURNTO = 'mockup2';

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
	if (nn == -1)
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

function rel(es, gs)
{
	return 100 - Math.abs(es-gs);
}

function bed(num)
{
	txt = " <img src='";
	if (num>=85)
		txt += "gs_gp.png";
	else if (num>=70)
		txt += "gs_yp.png";
	else
		txt += "gs_rp.png";
	txt += "' > " + (num).toString();
	return txt;
}

function mkTbl(nm, es, gs, utv)
{
	var n = nm.length;
	var txt = "<table class='visitab' > ";
	txt += " <tr class='visitab' > ";
	txt += " <th class='visitab' > # </th> ";
	txt += " <th class='visitab' > Kategori </th> ";
	txt += " <th class='visitab' > Jag </th> ";
	txt += " <th class='visitab' > Grupp </th> ";
	txt += " <th class='visitab' > Självbild </th> ";
	txt += " <th class='visitab' > Utv. </th> ";
	txt += " </tr> ";
	for (i=0; i<n; ++i)
	{
		txt += " <tr class='visitabrow' > ";
		txt += " <td class='visitabbx' > " + (i+1).toString() + " </td> ";
		txt += " <td class='visitabbx' > " + nm[i] + " </td> ";
		txt += " <td class='visitabbx' > " + bed(es[i]) + " </td> ";
		txt += " <td class='visitabbx' > " + gs[i] + " </td> ";
		sb = rel(es[i], gs[i]);
		txt += " <td class='visitabbx' > " + bed(sb) + "% </td> ";
		txt += " <td class='visitabbx' > " + "+" + utv[i].toString() + "%" + " </td> ";

		txt += " </tr> ";

	}

	txt += " </table> ";

	obj = document.getElementById("lstf");
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

function restSpdr()
{
	MA = document.getElementById("mainarea");
	MA.innerHTML = '<canvas id="SpiderCanvas" width="450" height="450" style="border:1px solid #000000;"> Din browser st&ouml;der inte canvas </canvas> '
}

function sp(i)
{
	restSpdr();

	targets = [ 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99 ];
	targ_s  = [ 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85  ];
	val_e_1 =   [ 78, 25, 34, 98, 56, 33, 90, 34, 56, 67, 23, 99, 78, 56, 65, 23, 99, 78, 56 ];
	val_b_1 =   [ 55, 56, 23, 67, 76, 34, 78, 34, 99, 12, 34, 34, 78, 34, 99, 12, 34, 88, 34 ];

	val_e_2 =   [ 34, 56, 67, 23, 99, 78, 56, 65, 23, 99, 78, 56 ];
	val_b_2 =   [ 34, 78, 34, 99, 12, 34, 34, 78, 34, 99, 12, 34, 88, 34 ];

	short_desc_1 = ['Värdegrund', 'Missionstatement'];
	short_desc_2 = ['Positiv', 'Äkta', 'Relevant'];
	short_desc_3 = ['Ärlig', 'Tillitsfull', 'Omdömesfull'];
	short_desc_4 = ['Motivation', 'Målsättning', 'Genomförande'];

	clearall();

	obj = document.getElementById("pl1" + i.toString());
	ss = "gp2.png";
	obj.src = ss ;

	setSpiderColors("#000", "#fff", "#777", "#888", "#2d1", "#fe0" );

	mhd = document.getElementById("mainhdr");

	switch (i)
	{
		case 1:
			mhd.innerHTML = "Värdegrund";
			DrawSpider('SpiderCanvas', 2, targets, targ_s, val_e_1, val_b_1, short_desc_1, "Värdegrund", true );
			//function mkTbl(nm, es, gs, utv)
			//PopLst(short_desc_1, 2);
			mkTbl(short_desc_1, val_e_1, val_b_1, [4,5,6]);
			break;
		case 2:
			mhd.innerHTML = "Omtyckt";
			DrawSpider('SpiderCanvas', 3, targets, targ_s, val_e_2, val_b_2, short_desc_2, 'PÄR', true );
			//PopLst(short_desc_2, 3);
			mkTbl(short_desc_2, val_e_2, val_b_2, [4,5,6]);
			break;
		case 3:
			mhd.innerHTML = "Klokskap";
			DrawSpider('SpiderCanvas', 3, targets, targ_s, val_e_1.slice(2), val_b_1.slice(2), short_desc_3, 'ÄTO', true );
			//PopLst(short_desc_3, 3);
			mkTbl(short_desc_3, val_e_1.slice(2), val_b_1.slice(2), [4,5,6]);
			break;
		case 4:
			mhd.innerHTML = "Mästarklass";
			DrawSpider('SpiderCanvas', 3, targets, targ_s, val_e_2.slice(1), val_b_2.slice(1), short_desc_4, 'MMG', true );
			mkTbl(short_desc_4, val_e_2.slice(1), val_b_2.slice(1), [4,5,6]);
			//PopLst(short_desc_4, 3);
			break;

	}
}

function tx(i)
{
	restSpdr();

	clearall();

	document.getElementById("mainhdr").innerHTML = "Min Fysik";

	obj = document.getElementById("pl35");
	ss = "gp2.png";
	obj.src = ss ;

	PopLst([], 0);

	canvas = document.getElementById("SpiderCanvas");
	var SZ = canvas.width;
	var ctx=canvas.getContext("2d");
	ctx.fillStyle="#fff";

	ctx.fillRect(0,0,SZ,SZ); 

	ctx.font="bold 20px Myriad-pro";
	ctx.fillStyle="#000";

	txt = "Lite text" ;

	txt_w = ctx.measureText(txt).width;
	ctx.fillText( txt, SZ/2-txt_w/2, 28 );

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

function dsk()
{
	restSpdr();

	clearall();

	document.getElementById("mainhdr").innerHTML = "Disk";

	obj = document.getElementById("pl15");
	ss = "gp2.png";
	obj.src = ss ;

	obj = document.getElementById("lstf");
	obj.innerHTML = ' &#128994; Egenskattning <br> &#128993; Gruppskattning ';

	rita_disc( document.getElementById("SpiderCanvas"), document.getElementById("Disc2"), 450, -2,3);
	rita_more( document.getElementById("SpiderCanvas"), 450, [{x:3,y:-3}, {x:6,y:8}] );
	
}

function st(i)
{
	clearall();

	obj = document.getElementById("pl2" + i.toString());
	ss = "gp2.png";
	obj.src = ss ;

	obj = document.getElementById("lstf");
	obj.innerHTML = '';

	mhd = document.getElementById("mainhdr");

	switch(i)
	{
		case 1:
			mhd.innerHTML = "Mål";
			func_1();
			break;
		case 2:
			mhd.innerHTML = "Stress";
			func_2();
			break;
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


	.visitab  {
		border: 1px solid black;
		border-collapse: collapse;
			
		padding-top:     6px;
		padding-left:   20px;
		padding-right:  20px;
		padding-bottom:  6px;
		text-align: left;
		background-color: #fff;
	}

	.visitabbx  {
		border: 1px solid black;
		border-collapse: collapse;
			
		padding-top:     6px;
		padding-left:   20px;
		padding-right:  20px;
		padding-bottom:  6px;
		text-align: left;
		/* background-color: #fff; */
	}

	.visitabrow:nth-child(odd) {
		background-color: #fee;
	}
	.visitabrow:nth-child(even) {
		background-color: #eef;
	}


</style>

<title> Mockup </title>

<?php

function survOut($tn, $filt)
{
	global $emperator;

	$pnr = "19721106-4634";
	$pid = 15;
	if ($pnr && ! $pid) {
		$query = "SELECT * FROM pers WHERE pnr='$pnr'";
		$res = mysqli_query($emperator, $query);
		if ($res) if ($row = mysqli_fetch_array($res))
			$pid = $row['pers_id'];
	}

	$n = 0;
	$query = "SELECT * FROM surv WHERE type='$tn' AND pers='$pid';";
	debug_log("query : " . $query);
	$res = mysqli_query( $emperator, $query );
	if ($res) while ($row = mysqli_fetch_array($res)) {
		$seq = $row['seq'];
		$sid = $row['surv_id'];
		++$n;
	}
	
	if ($n<=0) {
		return ' --- inga surveys ännu ---';
	} else if ($n==1) {
		$lnk = "onesurv.php?sid=$sid&seq=$seq&pid=$pid&st=$tn&filt=$filt";
		debug_log('embed link : ' . $lnk);
		return "<embed type='text/html' src='$lnk' width='450' height='450' >";
	} else {
		$lnk = "allsurv.php?pid=$pid&st=$tn&filt=$filt";
		debug_log('embed link : ' . $lnk);
		return "<embed type='text/html' src='$lnk' width='450' height='450' >";
	}
}


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


function index()
{
	debug_log("index()");

	$to = new tagOut;

	$to->startTag('script');

	$to->regline('function func_1() {');
	$to->regline(' obj = document.getElementById("mainarea"); ');
	$txt = " obj.innerHTML = " . '"' . survOut(104, 11) . '"';
	$to->regline($txt);
	$to->regline("}");

	$to->regline('function func_2() {');
	$to->regline(' obj = document.getElementById("mainarea"); ');
	$txt = " obj.innerHTML = " . '"' . survOut(101, 3) . '"';
	$to->regline($txt);
	$to->regline("}");
	
	$to->stopTag('script');

	$to->regLine( "\n</head>\n" );

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

	$to->regLine( '<div id="mainarea">' );
	$to->regLine( '<canvas id="SpiderCanvas" width="450" height="450" style="border:1px solid #000000;">' );
	$to->regLine( ' Din browser st&ouml;der inte canvas </canvas> ' );
	$to->regLine( '</div>' );

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
	outBtn($to, "pl12", "sp(2)", "Omtyckt (PÄR)");
	outBtn($to, "pl13", "sp(3)", "Klokskap (ÄTO)");
	outBtn($to, "pl14", "sp(4)", "Mästarklass (MMG)");
	outBtn($to, "pl15", "dsk()", "Disc");
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
	outBtn($to, "pl35", "tx(1)", "Min Fysik");
	outBtn($to, "pl36", "", false);
	$to->stopTag('table');

	$to->stopTag('td');

	$to->stopTag('tr');
	$to->startTag('tr');

	$to->startTag('td', 'colspan=3');
	$to->startTag('div', 'id="lstf"');
	$to->regLine('&#128994; Egenskattning <br> &#128993; Gruppskattning ');
	$to->stopTag('div');
	$to->stopTag('td');


	//$to->startTag('td', 'colspan=2');
	//$to->regLine('<img src="gg.png" \> <br> ');
	//$to->stopTag('td');

	$to->stopTag('tr');

	$to->stopTag('table');


	$to->stopTag('div'); //main
	$to->stopTag('body');


}


index();

?>

</html>
