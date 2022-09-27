

<script>

function range_slider_change(idx)
{
	inp = document.getElementById( "q" + idx );
	lbl = document.getElementById( "l" + idx );
	
	val = inp.value;
	lbl.innerHTML = " &nbsp; " + val + " &nbsp; ";
}

function Goto(str)
{
	str = "http://www." + <?php echo '"'.$BaseDomain.'"'; ?>  + "/" + str;
	window.location.assign( str );
}

function MakeNewUser(doc)
{
	str = "visste1.php";
	str += "?email" + "=" + doc.getElementById( "idx_email" ).value ;
	str += "&puls"  + "=" + doc.getElementById( "idx_puls"  ).value ;
	Goto(str);
}

function ank_keyp( a, b )
{
	doca = document.getElementById(a);
	docb = document.getElementById(b);
	textareavalue = docb.value;
	doca.extravalue = textareavalue;
}

function getRadioValue(formName, groupName)
{
	var radioGroup = document[formName][groupName];
	for (var i=0; i<radioGroup.length; i++)
	{
		if (radioGroup[i].checked) 
		{
			return radioGroup[i].value;
		}
	}
	return null;
}


var last_context ; 
var guide = null;

var saved_mo_info;

function save_moi( moi )
{
	if( ! ( saved_mo_info instanceof Array ) )
		saved_mo_info = new Array();
	var ii = saved_mo_info.length;
	if(ii==0)
		saved_mo_info = new Array();
	saved_mo_info[ii] = moi;
}

function Report( obj )
{
	var str = "[";
	var i, n = saved_mo_info.length;
	for(i=0; i<n; ++i)
	{
		if(i) str += ",";
		str += "{";
		str += saved_mo_info[i].x;
		str += ","+saved_mo_info[i].x;
		str += ","+saved_mo_info[i].w;
		str += ","+saved_mo_info[i].h;
		str += "}"
	}
	str += "]";
	document.getElementById(obj).innerHTML = "<code>" + str + "<\code><br>";
}

function DiscMouseMove(event)
{
	DiscMouseOut();
	ctx = last_context;
	ctx.font="bold 12px Myriad-pro";
	ctx.fillStyle="#000";
	x=Math.round(event.clientX-guide.getBoundingClientRect().left);
	y=Math.round(event.clientY-guide.getBoundingClientRect().top);

	var i, n=saved_mo_info.length;
	var f = "", ss = "";
	for( i=0; i<n; ++i )
	{
		var w2 = saved_mo_info[i].w / 2;
		var xx = 250 + 8 * saved_mo_info[i].x ;
		if( x < (xx-w2) ) continue;
		if( x > (xx+w2) ) continue;
		var h2 = saved_mo_info[i].h / 2;
		var yy = 250 + 8 * saved_mo_info[i].y ;
		if( y < (yy-h2) ) continue;
		if( y > (yy+h2) ) continue;
		ss = saved_mo_info[i].name;
	}

	if(ss=="") ss="(" + x + "," + y + ")";

	ctx.fillText(f+ss,1,245);
}

function DiscMouseOut()
{
	ctx = last_context;
	ctx.fillStyle="#7fff7f";
	ctx.fillRect(1,235,160,14);
	Krysset(ctx);
}

function Krysset( ctx )
{
	var W = 250;
	var M = 15;
	var P = 0.5;

	for(i=0;i<11;++i)
	{
		ctx.beginPath();
		ctx.arc(W,W,W-M,0,6.283);
		ctx.stroke();
	}
	ctx.beginPath();
	ctx.moveTo( W+P, P ); ctx.lineTo( W+P, W+W+P );
	ctx.moveTo( W-P, P ); ctx.lineTo( W-P, W+W+P );
	ctx.moveTo( P, W+P ); ctx.lineTo( W+W+P, W+P );
	ctx.moveTo( P, W-P ); ctx.lineTo( W+W+P, W-P );
	ctx.stroke();
}

function RitaBas( canvas )
{
	var c=document.getElementById(canvas);
	guide = c;
	var ctx=c.getContext("2d");
	last_context = ctx;

	var img = document.getElementById('dimg');
	ctx.drawImage(img, 0, 0);
}

function RitaDot( canvas, lr, ud )
{
	var c=document.getElementById(canvas);
	var ctx=c.getContext("2d");
	ctx.beginPath();
	ctx.fillStyle="#707070";
	ctx.arc( 250+8*lr, 250+8*ud, 7, 0,2*Math.PI );
	ctx.stroke();
	ctx.fill();
}

function RitaDotCol( canvas, lr, ud, col )
{
	var c=document.getElementById(canvas);
	var ctx=c.getContext("2d");
	ctx.beginPath();
	ctx.fillStyle=col;
	ctx.arc( 250+8*lr, 250+8*ud, 7, 0,2*Math.PI );
	ctx.stroke();
	ctx.fill();
}

function xyscale(i)
{
	return 250+8*i;
}

function RitaDotImg( canvas, lr, ud, img )
{
	var c=document.getElementById(canvas);
	var ctx=c.getContext("2d");
	var i=document.getElementById(img);
	ctx.drawImage(i,xyscale(lr)-i.width/2,xyscale(ud)-i.height/2);
}

function HideAway()
{
	document.getElementById("m1s").setAttribute("hidden","true");
	document.getElementById("m2s").setAttribute("hidden","true");
	document.getElementById("m3s").setAttribute("hidden","true");
	document.getElementById("dimg").setAttribute("hidden","true");
}

var loaded_count = 0;
var saved_canvas;
var saved_list;

function LoadedOne()
{
	++loaded_count;
}

function RitaManySaved()
{
	RitaBas(saved_canvas);
	for( i=0; i<saved_list.length; ++i )
	{
		if( saved_list[i].ico == 1 )
			RitaDotImg( saved_canvas, saved_list[i].x, saved_list[i].y, "m1s" );
		else if( saved_list[i].ico == 2 )
			RitaDotImg( saved_canvas, saved_list[i].x, saved_list[i].y, "m2s" );
		else if( saved_list[i].ico == 3 )
			RitaDotImg( saved_canvas, saved_list[i].x, saved_list[i].y, "m3s" );
		else
			RitaDot( saved_canvas, saved_list[i].x, saved_list[i].y );
	}

	setTimeout( HideAway, 1 );
}

function RitaManyIf()
{
	if( loaded_count >= 3 )
	{
		RitaManySaved();
	} else {
		setTimeout( RitaManyIf, 1 );
	}
}

function canvas_mouseover(e)
{
  var rect = this.getBoundingClientRect(),
      x = e.clientX - rect.left,
      y = e.clientY - rect.top,
      i = 0, r;
  
	var c=document.getElementById(saved_canvas);
	var ctx=c.getContext("2d");

	var i, found = -1, n = saved_list.length;
	for(i=0;i<n;++i)
	{
		lx = xyscale(saved_list[i].x);
		ly = xyscale(saved_list[i].y);
		dist = Math.hypot( x - lx , y - ly );
		if(dist<15)
		{
			found = i;
		}
	}

	var ctx=c.getContext("2d");
	ctx.fillStyle="#B3B3B3";
	ctx.fillRect(0,0,300,20); 

	if(found<0) return;

	ctx.fillStyle = "#000";
	ctx.font="16px Georgia";
	txt = saved_list[found].name;
	if(saved_list[found].isb)
		txt += " (boss)";
	ctx.fillText(txt,3,13);
}

function RitaMany( canvas, list )
{
	saved_list = list;
	saved_canvas = canvas;

	var c=document.getElementById(canvas);
	c.onmousemove = canvas_mouseover;

	RitaManyIf();
}

function RitaManyTest( canvas )
{
	RitaMany( canvas, [ {'x':2,'y':-3}, {'x':4,'y':6}, {'x':-5,'y':-2}, ] );
}

function Rita( canvas, lr, ud )
{
	RitaBas(canvas);
	RitaDot(canvas, lr, ud);
}

function sleepFor( sleepDuration )
{
	var timeout = new Date().getTime() + sleepDuration ;
	while(new Date().getTime() < timeout) { /* do nothing */ } 
}


function MyMakeChart( a, b, c )
{
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1.0', {'packages':['corechart'], callback:drawChart } );

	// Set a callback to run when the Google Visualization API is loaded.
	//google.setOnLoadCallback(drawChart);

	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {

		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Gap typ');
		data.addColumn('number', 'Summa gap');
		data.addRows([
			[ 'Materiella'     , a ],
			[ 'Sociala'        , b ],
			[ 'Professionella' , c ]
		]);

		// Set chart options
		var options = {
			'title'            : 'Gap Analys <alla>',
			'width'            : 400,
			'height'           : 300,
			'backgroundColor'  : '#8ec550'
			};

		// Instantiate and draw our chart, passing in some options.
		var MyDiv2 = document.getElementById("my_div_chart_2");
		// MyDiv2.innerHTML = "Nu!";
		// sleepFor(500);

		var chart = new google.visualization.BarChart( MyDiv2 );
		chart.draw(data, options);
	}

}

function min(a,b) { if( a < b ) return a; else return b; }

function MakeGauge( canvas, name, value )
{
	var c=document.getElementById(canvas);
	var ctx=c.getContext("2d");
	ww = c.width;
	hh = c.height;
	r = min(ww,hh)/2-10;
	x1 = ww / 2;
	y1 = hh / 2;

	ctx.beginPath();
	ctx.fillStyle="#fff";
	ctx.arc( x1,y1, r, 0,2*Math.PI );
	ctx.stroke();
	ctx.fill();

	ctx.font="bold 12px Myriad-pro";
	ctx.fillStyle="#000";
	ctx.fillText(name,10,12);

	deg = ((100-value)*2*Math.PI) / 100.0;
	x2 = x1+r*Math.sin(deg);
	y2 = x1+r*Math.cos(deg);

	ctx.beginPath();
	ctx.fillStyle="#fff";
	ctx.moveTo(x1,y1);
	ctx.lineTo(x2,y2);
	ctx.stroke();

	ctx.beginPath();
	ctx.fillStyle="#fff";
	ctx.strokeStyle="#000";
	ctx.arc( x1,y1, 9, 0,2*Math.PI );
	//ctx.stroke();
	ctx.fill();

	str = "";
	str += value;
	str += "%";

	ctx.font="9px Myriad-pro";
	ctx.fillStyle="#000";
	ctx.fillText(str,x1-7,y1+3);

}

function MakeMeter( canvas, name, value )
{
	var c=document.getElementById(canvas);
	var ctx=c.getContext("2d");
	ww = c.width;
	hh = c.height;

	x1 = ww / 2;
	y1 = hh / 2;

	var grd=ctx.createLinearGradient(0,0,0,hh);
	grd.addColorStop(0,   "green"  );
	grd.addColorStop(0.5, "yellow" );
	grd.addColorStop(1,   "red"    );

	ctx.fillStyle=grd;
	ctx.fillRect(0,0,ww,hh);

	ctx.font="bold 12px Myriad-pro";
	ctx.fillStyle="#000";
	ctx.fillText(name,10,12);

	y2 = (hh * (100-value)) / 100;
	ctx.beginPath();
	ctx.strokeStyle="#000";
	ctx.lineWidth=6;
	ctx.moveTo(8,y2);
	ctx.lineTo(ww-8,y2);
	ctx.stroke();

	ctx.beginPath();
	ctx.strokeStyle="#000";
	ctx.fillStyle="#fff";
	y3 = 3*hh/4;
	ctx.fillRect(30,y3,ww-60,12);

	ctx.font="9px Myriad-pro";
	ctx.fillStyle="#000";
	ctx.fillText( ""+value+"%",ww/2-8,y3+9);

}


var spider_canvas;
var spider_count;
var spider_targets;
var spider_targ_s;
var spider_val_e;
var spider_val_b;
var spider_shrt_desc;
var spider_mx;
var spider_my;
var spider_title;
var spider_gap_n;
var spider_gap_p;
var spider_gap_tot_n;
var spider_gap_tot_t;

function DrawSpider( canvas, count, targets, targ_s, val_e, val_b, shrt_desc, title )
{

	spider_canvas = document.getElementById(canvas);
	spider_count = count;
	spider_targets = targets;
	spider_targ_s = targ_s;
	spider_val_e = val_e;
	spider_val_b = val_b;
	spider_shrt_desc = shrt_desc;
	spider_mx = -1;
	spider_my = -1;
	spider_title = title;
	spider_gap_n = [];
	spider_gap_p = [];

	accu_n = 0;
	accu_p = 0;
	cnt = 0;
	for( i = 0 ; i<count ; ++i )
	{
		if( (val_e[i]!=0) && (val_b[i]!=0) )
		{
			val = ( val_e[i]*0.5 ) + ( val_b[i]*0.5 ) ;
		}
		else if( val_e[i]!=0 )
		{
			val = val_e[i] ;
		}
		else if( val_b[i]!=0 )
		{
			val = val_b[i] ;
		}
		else
		{
			continue;
		}
		if( targets[i] != 0 )
		{
			val_n = val / targets[i] ;
		} else {
			val_n = 0;
		}
		if( targ_s[i] != 0 )
		{
			val_p = val / targ_s[i] ;
		} else {
			val_p = 0;
		}
		spider_gap_n.push(val_n);
		spider_gap_p.push(val_p);
		if(val_n>1) val_n=1; // >100% kompenserar inte för annat
		if(val_p>1) val_p=1; // >100% kompenserar inte för annat
		accu_n += val_n;
		accu_p += val_p;
		++cnt;
	}

	spider_gap_tot_n =  ( accu_n / cnt );
	spider_gap_tot_p =  ( accu_p / cnt );

	spider_canvas.addEventListener( 'mousemove', spiderMouseMove, false );

	genericDrawSpider();
}

function getMousePos(canvas, evt)
{
	var rect = canvas.getBoundingClientRect();
	return {
		x: evt.clientX - rect.left,
		y: evt.clientY - rect.top
	};
}

function etDrawDisc()
{
}

function genericDrawSpider()
{
	var ctx=spider_canvas.getContext("2d");
	ww = spider_canvas.width;
	hh = spider_canvas.height;

	x1 = ww / 2;
	y1 = (hh-80) / 2 + 80;

	ctx.fillStyle="#fff";
	ctx.fillRect(0,0,ww,hh);

	ctx.font="bold 20px Myriad-pro";
	ctx.fillStyle="#000";

	txt = spider_title ;
	txt += ", medel ";
	sum=0;
	for( i=0; i< spider_count; ++i )
	{
		sum += spider_gap_n[i];
	}
	txt += (2.8).toFixed(1);

	txt_w = ctx.measureText(txt).width;
	ctx.fillText( txt, ww/2-txt_w/2, 28 );

	ctx.strokeStyle="#000";
	ctx.lineWidth=1;
	ctx.beginPath();
	ctx.moveTo(0,40);
	ctx.lineTo(ww,40);
	ctx.moveTo(0,80);
	ctx.lineTo(ww,80);
	ctx.stroke();

	sz = Math.min( ww*0.45, (hh-80)*0.45 );

	buff = sz / 5;

	dx = spider_mx - x1;
	dy = spider_my - y1;
	dist = Math.sqrt( dx*dx + dy*dy );

	ii = -1;

	if( dist < 5*buff )
	{
		ang = Math.atan2( dy, dx );
		ang += 4*Math.PI;
		ang += (2*Math.PI)/(2*spider_count);
		ii = Math.floor(spider_count * ang / (2*Math.PI)) ;
		ii = ii % spider_count;
		ctx.font="16px Myriad-pro";
		ctx.fillStyle="#000";

		txt = "anonym " +ii;
		txt_w = ctx.measureText(txt).width;
		ctx.fillText( txt, ww/2-txt_w/2, 28 + 40 );

	}

	ctx.strokeStyle="#888";
	ctx.lineWidth=1;

	for( i=1; i<=5; ++i )
	{
		ctx.beginPath();
		ctx.arc(x1,y1,buff*i,0,2*Math.PI);
		ctx.stroke();
	}

	ctx.strokeStyle="#f00";
	ctx.lineWidth=5;

	pm = Math.PI / spider_count;
	inc = 2*pm;

	for( i=0; i< spider_count; ++i )
	{

		ctx.strokeStyle="#7f7";

		ctx.beginPath();
		ctx.arc(x1,y1,buff*spider_targets[i],i*inc-pm,i*inc+pm);
		ctx.stroke();

		ctx.strokeStyle="#070";
		
		if( spider_targ_s[i] > 0 )
		{
			ctx.beginPath();
			ctx.arc(x1,y1,buff*spider_targ_s[i],i*inc-pm,i*inc+pm);
			ctx.stroke();
		}
	}

	offs = 2 * Math.PI / 350;

	ctx.font="8px Myriad-pro";
	ctx.fillStyle="#000";

	for( i=0; i< spider_count; ++i )
	{
		x2 = x1 + buff * spider_val_e[i] * Math.cos ( ( i * inc ) - offs );
		y2 = y1 + buff * spider_val_e[i] * Math.sin ( ( i * inc ) - offs );

		ctx.strokeStyle="#ccc";
		ctx.lineWidth=5;

		ctx.beginPath();
		ctx.moveTo(x1,y1);
		ctx.lineTo(x2,y2);
		ctx.stroke();

		x2 = x1 + buff * 5.3 * Math.cos ( ( i * inc ) - offs );
		y2 = y1 + buff * 5.3 * Math.sin ( ( i * inc ) - offs );

		if( i == ii )
		{
			ctx.strokeStyle="#0f0";
			ctx.lineWidth=1;
			ctx.beginPath();
			ctx.arc(x2,y2,9,0,2*Math.PI);
			ctx.stroke();
		}

		ctx.font="8px Myriad-pro";
		ctx.fillStyle="#000";
		txt = "" + (i+1); 
		mt = ctx.measureText(txt);
		txt_w = mt.width;
		txt_h = 8;
		ctx.fillText( txt, x2-txt_w/2, y2+txt_h/2 );

	}

	ctx.strokeStyle="#000";
	ctx.lineWidth=2;

	was_missing = false;

	for( i=0; i< spider_count; ++i )
	{
		if( ! spider_val_b[i] )
		{
		}
		else
		{
			x2 = x1 + buff * spider_val_b[i] * Math.cos ( ( i * inc ) + offs );
			y2 = y1 + buff * spider_val_b[i] * Math.sin ( ( i * inc ) + offs );

			ctx.beginPath();
			ctx.moveTo(x1,y1);
			ctx.lineTo(x2,y2);
			ctx.stroke();
		}

	}

	// legend

	ctx.lineWidth=5;
	ctx.font="bold 12px Myriad-pro";
	ctx.fillStyle="#000";

	ctx.strokeStyle="#ccc";
	ctx.beginPath();
	ctx.moveTo(8,hh-8-12*2);
	ctx.lineTo(28,hh-8-12*2);
	ctx.stroke();
	ctx.fillText("Egenskattning",32,hh-8-12*2+3);

	ctx.strokeStyle="#000";
	ctx.beginPath();
	ctx.moveTo(8,hh-8-12*1);
	ctx.lineTo(28,hh-8-12*1);
	ctx.stroke();
	ctx.fillText("Chefsskattning",32,hh-8-12*1+3);

	if(was_missing)
	{
		ctx.strokeStyle="#f00";
		ctx.beginPath();
		ctx.moveTo(8,hh-8-12*0);
		ctx.lineTo(28,hh-8-12*0);
		ctx.stroke();
		ctx.fillText("Värde saknas",32,hh-8-12*0+3);
	}

}

function spiderMouseMove(event)
{
	mousePos = getMousePos(spider_canvas, event);
	spider_mx = mousePos.x;
	spider_my = mousePos.y;
	genericDrawSpider();
}

function DrawSpiderTst( canvas, count, targets, val_e, val_b, shrt_desc, title )
{

	spider_canvas = document.getElementById(canvas);
	spider_count = count;
	spider_targets = targets;
	spider_val_e = val_e;
	spider_val_b = val_b;
	spider_shrt_desc = shrt_desc;
	spider_mx = -1;
	spider_my = -1;
	spider_title = title;

	spider_canvas.addEventListener( 'mousemove', spiderMouseMove, false );

	genericDrawSpider();

}

function CheckAndPostRecursive( parent, pattern, target, link )
{
	var ii = 0;
	var nn = parent.childNodes.length;
	for (ii=0; ii<nn; ++ii)
	{
		var obj = parent.childNodes[ii];

		if (typeof obj === 'undefined' || obj === null)
			continue;

		if (!CheckAndPostRecursive(obj, pattern, target, link))
			return false;

		var o_nm = obj.name;
		var o_id = obj.id;

		if (typeof o_nm === 'undefined' || o_nm === null)
			continue;

		if (typeof o_id === 'undefined' || o_id === null)
			continue;

		if (!o_id.match(pattern))
			continue;

		var val = obj.options[obj.selectedIndex].value;
		if (typeof val === 'undefined' || val === null || val === "")
		{
			return false;
		}
		link.target += "&" + o_nm + "=" + val;
	}
	return true;
}

function CheckAndPost( parent, pattern, errormessage, target )
{

	var link = { target:"" };
	link.target = target;

	ok = CheckAndPostRecursive(parent, pattern, target, link);

	if (!ok)
	{
		window.alert(errormessage);
	}
	else
	{
		window.location.assign( link.target );
	}
}

</script>


