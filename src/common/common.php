
<!-- inlude common.php -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>

function escapeHtml(unsafe)
{
	return unsafe
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
}
 
function range_slider_change(idx)
{
	inp = document.getElementById( "q" + idx );
	lbl = document.getElementById( "l" + idx );
	
	val = inp.value;
	lbl.innerHTML = " &nbsp; " + val + " &nbsp; ";
}

function corr1(idnum, corrval) {
  var elem = document.getElementById('QI-' + idnum);
  var sel = document.querySelector('input[name="' + idnum + '"]:checked');
  if (sel === null) {
    elem.src = '../common/blank.png';
    return false;
  } else {
    var ok = (sel.value == corrval);
    elem.src = ok ? "../common/corr.png" : "../common/err.png";
    return ok;
  }
}

function showTime() {
  var t1 = document.getElementById("TimeStart").value;
  //var t2 = document.getElementById("TimeStop").value;
  var t3 = document.getElementById("TimeMax").value;
  //if (t2 == "")
  var t2 = (new Date()).getTime().toString();
  var diff = parseInt(t2) - parseInt(t1);
  var rem = parseFloat(t3) - (diff/1000.0);
  rem = Math.round(rem * 10) / 10;

  if (rem < 0) {
    document.getElementById("TimerDisplay").innerHTML = 'Tiden Slut';
  } else {
    st = rem.toString();
    if (!st.includes("."))
        st = st + '.0';
    document.getElementById("TimerDisplay").innerHTML = 'Tid: ' + st + ' s';
  }
}

function doShow() {
  document.getElementById('QueryBox').style.display = "block";
  document.getElementById('StartBtn').style.display = "none";
  document.getElementById("AudioBox").play();
  document.getElementById("TimeStart").value = (new Date()).getTime().toString();

  setInterval(showTime, 150);
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


function NONgenericDrawSpider()
{
	var ctx=spider_canvas.getContext("2d");
	ww = spider_canvas.width;
	hh = spider_canvas.height;

	x1 = ww / 2;
	y1 = (hh-80) / 2 + 80;

	ctx.fillStyle="#eee";
	ctx.fillRect(0,0,ww,hh);

	ctx.font="bold 20px Myriad-pro";
	ctx.fillStyle="#000";

	txt = spider_title ;
	//txt += ", medel ";
	//sum=0;
	//for( i=0; i< spider_count; ++i )
	//{
	//	sum += spider_gap_n[i];
	//}
	//txt += (20*sum/spider_count).toFixed(1);

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

		txt = spider_shrt_desc[ii];
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

		ctx.strokeStyle="#222";
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
		txt = spider_val_e[i].toFixed(1);
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

	ctx.strokeStyle="#fff";
	ctx.beginPath();
	ctx.moveTo(8,hh-8-12*2);
	ctx.lineTo(28,hh-8-12*2);
	ctx.stroke();
	ctx.fillText("Egenskattning",32,hh-8-12*2+3);

	// ctx.strokeStyle="#000";
	// ctx.beginPath();
	// ctx.moveTo(8,hh-8-12*1);
	// ctx.lineTo(28,hh-8-12*1);
	// ctx.stroke();
	// ctx.fillText("Chefsskattning",32,hh-8-12*1+3);

	if (was_missing)
	{
		ctx.strokeStyle="#f00";
		ctx.beginPath();
		ctx.moveTo(8,hh-8-12*0);
		ctx.lineTo(28,hh-8-12*0);
		ctx.stroke();
		ctx.fillText("Värde saknas",32,hh-8-12*0+3);
	}

}

function getMousePos(canvas, evt)
{
	var rect = canvas.getBoundingClientRect();
	return {
		x: evt.clientX - rect.left,
		y: evt.clientY - rect.top
	};
}

function spiderMouseMove(event)
{
	mousePos = getMousePos(spider_canvas, event);
	spider_mx = mousePos.x;
	spider_my = mousePos.y;
	genericDrawSpider();
}

</script>

<style type="text/css">

	.btndiv {
		display: inline;
	}
  
	table {
		border-collapse:separate; 
		border-spacing: 0 1em;
	}

	.main {
		margin-left: 15px;  /* Same as the width of the sidenav */
		margin-right: 260px;  /* Same as the width of the sidenav */
		font-size: 28px; /* Increased text to enable scrolling */
		padding: 0px 10px;
	}
	body {
		margin-bottom: 75px;
	}

</style>

<?php

include_once 'getparam.php';
include_once 'convert.php';

function redirect($link)
{
	$str  = '<meta http-equiv="Refresh" content="0; url=';
	$str .=	"'" . $link . "'";
	$str .= '" />';
	return $str;
}

function QIT($val)
{
	if (is_numeric($val))
		return $val;
	else
		return "'" . $val . "'";
}

function COR( $db, $id_n, $id_v, $sup_n, $sup_v, $res_n ) // Create Or Read
{
	global $emperator;

	$query = "SELECT * FROM " . $db . " WHERE";
	
	$n = count($id_n);
	for ($i=0; $i<$n; ++$i) {
		if ($i==0)
			$query .= " ";
		else
			$query .= " AND ";
		$query .= $id_n[$i] . "=" . QIT($id_v[$i]);
	}
	$res = mysqli_query($emperator, $query);
	if ($res) {
		$row = mysqli_fetch_array($res);
		if ($row) {
			return $row[$res_n];
		}
	}
	
	$nn = " (";
	$vv = " (";
	$n = count($id_n);
	$sep = "";
	for ($i=0; $i<$n; ++$i) {
		$nn .= $sep . $id_n[$i];
		$vv .= $sep . QIT($id_v[$i]);
		$sep = ",";
	}
	$n = count($sup_n);
	for ($i=0; $i<$n; ++$i) {
		$nn .= $sep . $sup_n[$i];
		$vv .= $sep . QIT($sup_v[$i]);
		$sep = ",";
	}
	$nn .= ")";
	$vv .= ")";
	
	$query = "INSERT INTO " . $db . $nn . " VALUES " . $vv;
	
	$res = mysqli_query($emperator, $query);
	if ($res) {
		return $emperator->insert_id;
	} else {
		return 'DB Error, insert --'.$query.'-- ' . "\n" . mysqli_error();
	}
}

function COU( $db, $id_n, $id_v, $sup_n, $sup_v, $key ) // Create Or Update
{
	global $emperator;

	$query = "SELECT * FROM " . $db . " WHERE";
	
	$n = count($id_n);
	for ($i=0; $i<$n; ++$i) {
		if ($i==0)
			$query .= " ";
		else
			$query .= " AND ";
		$query .= $id_n[$i] . "=" . QIT($id_v[$i]);
	}
	$res = mysqli_query($emperator, $query);
	if ($res) {
		$row = mysqli_fetch_array($res);
		if ($row) {
			
			$kval = $row[$key];
			
			$query = "UPDATE " . $db . " SET ";
			
			$sep = "";
			$n = count($sup_n);
			for ($i=0; $i<$n; ++$i) {
				$query .= $sep . $sup_n[$i] . "=" . QIT($sup_v[$i]);
				$sep = ",";
			}
			
			$query .= " WHERE " . $key . "=" . $kval;
			
			$res = mysqli_query($emperator, $query);
			
			return $res;
		}
	}
	
	$nn = " (";
	$vv = " (";
	$n = count($id_n);
	$sep = "";
	for ($i=0; $i<$n; ++$i) {
		$nn .= $sep . $id_n[$i];
		$vv .= $sep . QIT($id_v[$i]);
		$sep = ",";
	}
	$n = count($sup_n);
	for ($i=0; $i<$n; ++$i) {
		$nn .= $sep . $sup_n[$i];
		$vv .= $sep . QIT($sup_v[$i]);
		$sep = ",";
	}
	$nn .= ")";
	$vv .= ")";
	
	$query = "INSERT INTO " . $db . $nn . " VALUES " . $vv;
	
	$res = mysqli_query($emperator, $query);

	return $res;

}


















?> 
