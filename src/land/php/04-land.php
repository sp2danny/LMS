
<?php

include "00-common.php";
include "00-connect.php";

include_once "../../site/common/debug.php";

$styr = LoadIni("../styr.txt");

$lid      = getparam('lid');
$variant  = 0;
$cid      = 0;
$pnr      = getparam('pnr', false);
$pid      = false;

if ($pnr) {
	$query = "SELECT * FROM pers WHERE pnr='$pnr'";
	$res = mysqli_query($emperator, $query);
	if ($res) if ($row = mysqli_fetch_array($res))
	{
		$pid = $row['pid'];
	}
}

$query = "SELECT * FROM data WHERE type=17 AND value_a='$lid'";
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res))
{
	$variant = $row['value_b'];
}

$query = "SELECT * FROM data WHERE type=71 AND pers=0 AND value_a=$variant";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$cid = $row['value_b'];
}

$pkv = "0";
$query = "SELECT * FROM data WHERE type=50 AND pers=0";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$pkv = $row['value_a'];
	$dagar = $row['value_b'];
	$startd = $row['date'];
}

$query = "SELECT * FROM data WHERE type=70 AND pers=0";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$pkv = $row['value_a'];
	$dagar = $row['value_b'];
	$startd = $row['date'];
}

$dt = date_create_from_format("Y-m-d H:i:s",$startd);
date_add($dt, date_interval_create_from_date_string($dagar . " days"));
$ttt = date_format($dt, "Y-m-d H:i:s");

$eol = "\n";

$dts = [];

$n = get_styr($styr, "prod", "prod.num", $variant);
for ($i = 1; $i<=$n; ++$i)
{
	$ss = get_styr($styr, "prod", "prod." . $i . ".cdate", $variant);
	if ($ss)
		$dts[] = $ss;
}

$found = false;

foreach ($dts as $val)
{
	$dt_tmp = date_create_from_format("Ymd", $val);
	if (!$found)
	{
		$found = true;
		$dt = $dt_tmp;
	}
	else if ($dt_tmp < $dt) {
		$dt = $dt_tmp;
	}
}

if ($found)
{
	$ttt = date_format($dt, "Y-m-d H:i:s");
	//debug_log("found " . $ttt);
}
else {
	$ttt = date_format($dt, "Y-m-d H:i:s");
	//debug_log("not found " . $ttt);
}

$lnk_none   =  get_styr($styr, 'prod', "link.none",  $variant);
$lnk_total  =  get_styr($styr, 'prod', "link.total", $variant);
$lnk_save   =  get_styr($styr, 'prod', "link.save",  $variant);
$lnk_cta    =  get_styr($styr, 'prod', "link.cta",   $variant);


?>

<!DOCTYPE html>

<html>

<head>

	<title> <?php echo get_styr($styr,"common","title",$variant); ?> </title>

<!-- Privacy-friendly analytics by Plausible -->
<script async src="https://plausible.io/js/pa-_BRYh01HblRT2nrTNkHQm.js"></script>
<script>
  window.plausible=window.plausible||function(){(plausible.q=plausible.q||[]).push(arguments)},plausible.init=plausible.init||function(i){plausible.o=i||{}};
  plausible.init()
</script>

	<link rel="stylesheet" href="../main-v03.css" />
	<link rel="icon" href="../../site/common/favicon.ico" />
 
	<?php include "00-style.php"; ?>

	<?php
		$pr_mr = '{"0":0}';
		$pr_price = 0;
		$pmain = get_styr($styr, 'prod', 'prod.main', $variant);
		debug_log("pmain : " . $pmain);
		$query = "SELECT * FROM prod WHERE prod_id=" . $pmain;
		$res = mysqli_query( $emperator, $query );
		if ($res) if ($row = mysqli_fetch_array($res)) {
			$pr_mr = $row['MR'];
			debug_log("pr_mr : " . $pr_mr);
			$pr_price = $row['price'];
			debug_log("pr_price : " . $pr_price);
		}
		$rebate = json_decode($pr_mr);

		function reb($i)
		{
			global $rebate;
			$p = 0;
			foreach ($rebate as $k=>$v)
			{
				if ($k <= $i)
					if ($v > $p)
						$p = $v;
			}
			return $p;
		}


		$select = get_styr($styr, 'prod', 'prod.select', $variant);
		$subs = explode(",", $select);

		$pr_title_arr = [];
		$pr_desc_arr = [];
		$pr_price_arr = [];
		$pr_img_arr = [];

		$nn = 0;

		foreach ($subs as $k=>$v) {
			$query = "SELECT * FROM prod WHERE prod_id=" . $v;
			$res = mysqli_query( $emperator, $query );
			if ($res) if ($row = mysqli_fetch_array($res)) {
				$pr_title_arr[] = $row['name'];
				$pr_desc_arr[]  = $row['pdesc'];
				$pr_price_arr[] = $row['price'];
				$pr_img_arr[]   = $row['image'];
				++$nn;
			}
		}

		echo "<script>\n";

		echo "let sel = ["; // false, false, false]; \n";
		for ($i=0; $i<$nn; ++$i) {
			if ($i != 0) echo ", ";
			echo "false";
		}
		echo "]; \n\n";


		echo "function sel_p() { \n";
		echo "  var sp = 0; \n";
		for ($i=0; $i<$nn; ++$i)
		{
			echo "  if (sel[$i]) sp += " . $pr_price_arr[$i] . "; \n";
		}
		//if (numsel > 1)
			
		echo "  if (sp==0) sp = $pr_price; \n";
		echo "  return sp;\n";
		echo "}\n\n";

	?>

		function mkpr(i) {
			switch (true) {
				<?php
					echo "\n";
					$same = true;
					$last = 0;
					for ($i=0; $i<=100; ++$i)
					{
						$pr = reb($i);
						if ($pr == $last)
							continue;
						echo t(4) . "case (i<$i) : return $last;\n";
						$last = $pr;
					}
					echo t(4) . "default: return $last;\n";
				?>
			}
		}

		var numsel = 0;

		var antal = 1;

		function kmps_btn()
		{
			var div = document.getElementById("kmps");
			div.innerHTML = 
				" <h3> Best&auml;ll flera, f&aring; rabatt </h3> <br /> \n" +
				" <label for='qtt'> Antal: </label> \n" +
				" <input onChange='upd_cnt()' value='1' type='number' id='qtt' name='qtt' min='1' > \n" ;

		}

		function upd_cnt()
		{
			antal = parseInt(document.getElementById("qtt").value);
			if (antal<1) antal=1;

			on_update_3(sel_p());
			on_update_2();
		}

		function nicep(txt)
		{
			var ll = txt.length;
			if (ll>3) {
				txt = txt.slice(0, ll-3) + " " + txt.slice(ll-3);
			}
			return txt;
		}

		var first_many = true;

		function nu_banger(ppp)
		{
			var canvas = document.getElementById("priceCanv");
			var ctx = canvas.getContext("2d");
			var img = document.getElementById("priceImg");
			ctx.drawImage(img, 0, 0, 140, 140);
			ctx.font = "32px roboto";

			//var txt1 = nicep(ppp.toString());
			var txt1 = "10 st";
			var xx1 = (140 - ctx.measureText(txt1).width)/2;
			ctx.fillText(txt1, xx1, 50);

			let reb = mkpr(10);
			let pr = sel_p();
			let rr = (100 - reb) / 100;
			let ap = Math.floor(pr * rr);

			var txt2 = (ap).toString() + ":-";
			var xx2 = (140 - ctx.measureText(txt2).width)/2;
			ctx.fillText(txt2, xx2, 90);

			var txt3 = "Betala via klarna";
			ctx.font = "12px roboto";
			var xx3 = (140 - ctx.measureText(txt3).width)/2;
			ctx.fillText(txt3, xx3, 110);

			//var canvas = document.getElementById("prisCanv2");
			//var ctx = canvas.getContext("2d");
			//ctx.drawImage(img, 0, 0, 140, 140);
			//ctx.font = "32px roboto";
			//ctx.fillText(txt1, xx1, 50);
			//ctx.fillText(txt2, xx2, 90);
			//ctx.font = "12px roboto";
			//ctx.fillText(txt3, xx3, 110);

			return true;
		}

		var sav;

		function nu_price(ppp)
		{
			//if (numsel > 1) first_many = false;
			//if (antal > 1) first_many = false;

			//if (first_many)
			
			nu_banger(ppp);

			var canvas = document.getElementById("priceCanv");
			var ctx = canvas.getContext("2d");
			var img = document.getElementById("priceImg");
			//ctx.drawImage(img, 0, 0, 140, 140);
			ctx.font = "32px roboto";

			old_ppp = ppp;

			let sumreb = (100-mkpr(numsel)) / 100;
			sumreb *= (100-mkpr(antal)) / 100;
			sumreb *= 100;
			sumreb = Math.floor(100-sumreb);

			ppp = Math.floor ( ppp * (100-sumreb) / 100 ) ;

			sav = antal * numsel * (old_ppp-ppp);

			var txt1 = nicep(ppp.toString());
			txt1 += ":-";
			var xx1 = (140 - ctx.measureText(txt1).width)/2;
			//ctx.fillText(txt1, xx1, 90);

			var txt2 = antal.toString() + " st";
			var xx2 = (140 - ctx.measureText(txt2).width)/2;
			//ctx.fillText(txt2, xx2, 50);

			var txt3 = "Betala via klarna";
			ctx.font = "12px roboto";
			var xx3 = (140 - ctx.measureText(txt3).width)/2;
			//ctx.fillText(txt3, xx3, 110);

			var canvas = document.getElementById("prisCanv2");
			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0, 140, 140);
			ctx.font = "32px roboto";
			ctx.fillText(txt1, xx1, 90);
			ctx.fillText(txt2, xx2, 50);
			ctx.font = "12px roboto";
			ctx.fillText(txt3, xx3, 110);

			return true;
		}

		function on_update_3(ppp)
		{
			//debug_log("on_update_3 with ppp = " . $ppp);
			nu_price(ppp);

			old_ppp = ppp;

			let sumreb = (100-mkpr(numsel)) / 100;
			sumreb *= (100-mkpr(antal)) / 100;
			sumreb *= 100;
			sumreb = Math.floor(100-sumreb);

			ppp = Math.floor ( ppp * (100-sumreb) / 100 ) ;

			sav = antal * (old_ppp-ppp);


			var bnb = document.getElementById("bnb");
			if (numsel == 0)
			{
				bnb.disabled = true;
				txt = <?php echo '"' . $lnk_none . '"'; ?> ;
				bnb.innerHTML = txt; 
			} else {
				bnb.disabled = false;
				var txt = <?php echo '"' . $lnk_total . '"'; ?> ;
				txt = txt.replace("%tot%", nicep((antal * ppp).toString()));
				//var txt = "Totalt " + nicep((antal * numsel * ppp).toString()) + ":- <br> ";
				if (sav > 0) {
					var savt = <?php echo '"' . $lnk_save . '"'; ?> ;
					savt = savt.replace("%sav%", nicep(sav.toString()));
					txt += " <br> " + savt; // Du sparar " + nicep(sav.toString()) + ":- <br> ";
				}
				cta = <?php echo '"' . $lnk_cta . '"'; ?> ;
				txt += " <br> " + cta;
				bnb.innerHTML = txt; 
			}
		}


		function doclick(i)
		{
			//global $pr_price;
			sel[i] = !sel[i];
			numsel = 0;
			for (let i = 0; i < 3; i++) {
				var cb = document.getElementById("cb_" + i.toString());
				if (!cb) continue;
				cb.checked = sel[i];
				if (sel[i]) ++numsel;
			}

			pr = sel_p();
			on_update_3(pr);
		}

		function buynow(lnk)
		{
			var sel_id = [
			<?php
				$select = get_styr($styr, 'prod', 'prod.select', $variant);
				echo $select;
			?>
			];

			if (numsel == 0)
				return false;
			let first = true;
			for (let i = 0; i < 3; i++) {
				if (sel[i]) {
					if (first)
						lnk += "&prod=" + sel_id[i].toString();
					else
						lnk += "," + sel_id[i].toString();
					first = false;
				}
			}
			let sumreb = (100-mkpr(numsel)) / 100;
			sumreb *= (100-mkpr(antal)) / 100;
			sumreb *= 100;
			lnk += "&reb=" + Math.floor(100-sumreb).toString();
			lnk += "&qtt=" + antal.toString();
			window.location.href = lnk;
		}

		function on_update_2()
		{
			var canvas = document.getElementById("circCanv");
			var ctx = canvas.getContext("2d");
			var img = document.getElementById("circImg");

			ctx.clearRect(0, 0, canvas.width, canvas.height);
			ctx.globalAlpha = 0.4;
			ctx.drawImage(img, 0, 0); 
			ctx.globalAlpha = 1.0;
			ctx.font = "42px roboto";

			<?php 
				echo t(4) . "var pkv = $pkv; \n";
				//echo t(4) . "var txt = " . "'" . $pkv . " platser';\n";
				echo t(4) . "var dt = new Date('" . $ttt . "').getTime();\n";
			?>

			var txt = (pkv-antal).toString() + ' platser';

			var xx = (384 - ctx.measureText(txt).width)/2;
			ctx.fillText(txt, xx, 175);
			txt = "kvar";
			xx = (384 - ctx.measureText(txt).width)/2;
			ctx.fillText(txt, xx, 255);

			canvas = document.getElementById("timeCanv");
			ctx = canvas.getContext("2d");
			ctx.clearRect(0, 0, canvas.width, canvas.height);
			ctx.globalAlpha = 0.4;
			ctx.drawImage(img, 0, 0); 
			ctx.globalAlpha = 1.0;
			ctx.font = "28px roboto";

			var now = new Date().getTime();
			var distance = dt - now;

			var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			var seconds = Math.floor((distance % (1000 * 60)) / 1000);
				
			var start = 145;
			var offs = 28;

			txt = days.toString() + " dagar"
			xx = (384 - ctx.measureText(txt).width)/2;
			ctx.fillText(txt, xx, start + 0*offs);
				
			txt = hours.toString() + " timmar"
			xx = (384 - ctx.measureText(txt).width)/2;
			ctx.fillText(txt, xx, start + 1*offs);

			txt = minutes.toString() + " minuter"
			xx = (384 - ctx.measureText(txt).width)/2;
			ctx.fillText(txt, xx, start + 2*offs);
				
			txt = seconds.toString() + " sekunder"
			xx = (384 - ctx.measureText(txt).width)/2;
			ctx.fillText(txt, xx, start + 3*offs);

			txt = "kvar";
			xx = (384 - ctx.measureText(txt).width)/2;
			ctx.fillText(txt, xx, start + 4*offs);
				
			setTimeout(on_update_2, 333);
		}

	</script>

</head>

<body>
	<div id='dbg'>
	<br>
	</div>
	<div>
		<br /> 
		<img width=50% src="../../site/common/logo.png" /> <br />
		<br /> <br /> 

		<?php

			$n = get_styr($styr, "land", "num", $variant);
			for ($i = 1; $i<=$n; ++$i)
			{
				$t = get_styr($styr, "land", "text." . $i , $variant);
				echo $t . "\n";
				if ($i < $n)
				{
					echo "<br /> <hr /> \n";
				}
			}

			echo "<table> <tr> <td> \n";
			echo t(4) . "<p class='blurb'> \n";

			echo get_styr($styr, 'prod', 'prod.text', $variant);

			$text = "";

			$pid = get_styr($styr, 'prod', 'prod.main', $variant);

			$select = get_styr($styr, 'prod', 'prod.select', $variant);

			$pr_title = '';
			$pr_desc = '';
			$pr_price = 0;
			$pr_img = '';
			$query = "SELECT * FROM prod WHERE prod_id=" . $pid;

			//debug_log("got data for prod $pid");

			$res = mysqli_query( $emperator, $query );
			if ($res) if ($row = mysqli_fetch_array($res)) {
				$pr_title = $row['name'];
				$pr_desc  = $row['pdesc'];
				$pr_price = $row['price'];
				$pr_img   = $row['image'];
				$pr_unl   = $row['unlocks'];
				debug_log(" pr_price : $pr_price ");
			}

			echo t(4) . $text . "\n";
			echo t(4) . "<br> </p> \n";

			echo t(4) . " </td> <td> ";
			echo " &nbsp;&nbsp; ";

			echo " </td> <td> ";
			echo " <canvas id='priceCanv' width='140' height='140' > </canvas> ";
			echo " </td> </tr> </table> \n";

			$pitch = get_styr($styr, 'result', 'pitch.text', $variant);
			$pitch = str_replace("%price%", $pr_price, $pitch);
					
			echo t(4) . "<div class='pitch'> " . $pitch . " </div>\n";

			$many = "04b-many.php?lid=" . $lid;

			$subs = explode(",", $select);

			$pr_title_arr = [];
			$pr_desc_arr = [];
			$pr_price_arr = [];
			$pr_img_arr = [];

			foreach ($subs as $k=>$v) {
				$query = "SELECT * FROM prod WHERE prod_id=" . $v;
				$res = mysqli_query( $emperator, $query );
				if ($res) if ($row = mysqli_fetch_array($res)) {
					$pr_title_arr[] = $row['name'];
					$pr_desc_arr[]  = $row['pdesc'];
					$pr_price_arr[] = $row['price'];
					$pr_img_arr[]   = $row['image'];
				}
			}

			debug_log("pr_price_arr " . arr2str($pr_price_arr));

			$i = 0; $n = count($subs);
			//echo " <br> \n";
			echo " <table> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td onClick='doclick($i)' > <h3> ";
				echo $pr_title_arr[$i];
				echo " </h3> </td> ";
			}
			echo " </tr> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td  onClick='doclick($i)' style='padding-right:12px' > <img width='575px' src='/article/";
				echo $pr_img_arr[$i];
				echo "' > </td> ";
			}
			echo " </tr> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td  onClick='doclick($i)' style='padding-right:12px' > ";
				echo str_replace("\r\n", " <br> ", $pr_desc_arr[$i]);
				echo " <br> <br> </td> ";
			}

			echo " </tr> <tr> ";

			for ($i=0; $i<$n; ++$i) {

				$dd = get_styr($styr, "prod", "prod." . ($i+1) . ".date", $variant);

				echo "	<td> \n";
				echo "		<table> \n";
				echo "			<tr> \n";
				echo "				<td> \n";
				echo "					Ord pris <br> \n";
				echo "					<div style='color:red' > \n";
				echo "					" . $pr_price_arr[$i] . " \n";
				echo "					</div> \n";
				echo "				</td> \n";
				echo "				<td> \n";
				echo "					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; \n";
				echo "				</td> \n";
				echo "				<td> \n";
				echo "					<input id='cb_$i' type='checkbox' onclick='doclick($i)' > \n";
				echo "					V&auml;lj h&auml;r \n ";
				//if ($i==0) echo "<br> &nbsp;&nbsp;&nbsp;4 dec kl 10 \n";
				//if ($i==1) echo "<br> &nbsp;&nbsp;&nbsp;11 dec kl 10 \n";
				//if ($i==2) echo "<br> &nbsp;&nbsp;&nbsp;18 dec kl 10 \n";
				echo "                  <br> &nbsp;&nbsp;&nbsp;" . $dd . " \n";
				echo "				</td> \n";
				echo "			</tr> \n";
				echo "		</table> \n";
				echo "	</td> \n";
			}

			echo " </tr> <tr> ";

			echo " <td colspan=3 > ";

			echo " <h3> Best&auml;ll flera, f&aring; rabatt </h3>  \n";

			echo " <label for='qtt'> Antal: </label> \n" .
				" <input style='font-size: 125%; width:125px; ' onChange='upd_cnt()' value='1' type='number' id='qtt' name='qtt' min='1' > \n" ;


			echo " </td> </tr> <tr> ";

			echo " <td colspan=3 > ";


			$lnk_u = get_styr($styr, 'prod', "link.url", $variant);
			if (strpos($lnk_u, '?'))
				$lnk_u .= "&id=" . $lid;
			else
				$lnk_u .= "?id=" . $lid;
			//$lnk_u .= "&prod=" . $pid;

			$many = "04b-many.php?lid=" . $lid;
			//echo " <a href='$many'> Best&auml;ll flera </a> <br> <br> \n";


			echo " <button onClick='buynow(\"$lnk_u\")' disabled='true' id='bnb' class='shake_green' > $lnk_none </button> </a> ";


			echo " </td> </tr> <tr> <td colspan=3 > ";


			echo "<table> ";
			echo " <tr> ";
			echo " <td> &nbsp;&nbsp; </td> ";
			echo " <td> <canvas id='circCanv' width='384' height='384' > </canvas> </td> ";
			echo " <td> &nbsp;&nbsp; </td> ";
			echo " <td> <canvas id='timeCanv' width='384' height='384' > </canvas> </td> ";
			echo " <td> &nbsp;&nbsp; </td> ";
			echo " </tr> </table> ";

			echo " </td> ";
			echo " <td> <canvas id='prisCanv2' width='140' height='140' > </canvas> </td> ";

			echo " </tr> </table> <br> \n";

			echo " </td> </tr> </table> ";

		?>

		<br /> <br /> 

	</div>

	<div style="display:none" >
		<img id='circImg' src='../red-circle.png' onload='on_update_2()' /> 
	</div>
	<div style="display:none" >
		<img id='priceImg' src='../pris.jpg' onload='on_update_3(sel_p())' /> \n";
	</div>

</body>
 
</html>
