
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

$bid = $variant;

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

?>

<!DOCTYPE html>

<html>

<head>

	<title> <?php echo get_styr($styr,"common","title",$variant); ?> </title>

	<link rel="stylesheet" href="../main-v03.css" />
	<link rel="icon" href="../../site/common/favicon.ico" />
 
	<?php include "00-style.php"; ?>

	<script>

		function on_update_3(ppp)
		{
			var canvas = document.getElementById("priceCanv");
			var ctx = canvas.getContext("2d");
			var img = document.getElementById("priceImg");
			ctx.drawImage(img, 0, 0, 140, 140);
			ctx.font = "32px roboto";
			var txt1 = ppp.toString();
			var ll = txt1.length;
			if (ll>3) {
				txt1 = txt1.slice(0, ll-3) + " " + txt1.slice(ll-3);
			}
			txt1 += ":-";
			var xx1 = (140 - ctx.measureText(txt1).width)/2;
			ctx.fillText(txt1, xx1, 90);

			var txt2 = "Nu!";
			var xx2 = (140 - ctx.measureText(txt2).width)/2;
			ctx.fillText(txt2, xx2, 50);

			var txt3 = "Betala via klarna";
			ctx.font = "12px roboto";
			var xx3 = (140 - ctx.measureText(txt3).width)/2;
			ctx.fillText(txt3, xx3, 110);

			canvas = document.getElementById("prisCanv2");
			ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0, 140, 140);
			ctx.font = "32px roboto";
			ctx.fillText(txt1, xx1, 90);
			ctx.fillText(txt2, xx2, 50);
			ctx.font = "12px roboto";
			ctx.fillText(txt3, xx3, 110);
		}

		let sel = [false, false, false];

		function doclick(i)
		{
			// var div = document.getElementById("dbg");
			// div.innerHTML = "Clicked " + i.toString() + " <br>\n";

			sel[i] = !sel[i];
			for (let i = 0; i < 3; i++) {
				var cb = document.getElementById("cb_" + i.toString());
				cb.checked = sel[i];
			}

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
				echo t(4) . "var txt = " . "'" . $pkv . " platser';\n";
				echo t(4) . "var dt = new Date('" . $ttt . "').getTime();\n";
			?>

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

			$pid = get_styr($styr, 'prod', 'prod.num', $variant);

			$select = get_styr($styr, 'prod', 'prod.select', $variant);

			$pr_title = '';
			$pr_desc = '';
			$pr_price = 0;
			$pr_img = '';
			$query = "SELECT * FROM prod WHERE prod_id=" . $pid;

			debug_log("got data for prod $pid");

			$res = mysqli_query( $emperator, $query );
			if ($res) if ($row = mysqli_fetch_array($res)) {
				$pr_title = $row['name'];
				$pr_desc  = $row['pdesc'];
				$pr_price = $row['price'];
				$pr_img   = $row['image'];
				$pr_unl   = $row['unlocks'];
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
			// echo " <a href='$many'> Best&auml;ll flera </a> <br> <br> \n";



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

			$i = 0; $n = count($subs);
			echo " <br> \n";
			echo " <table> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td onClick='doclick($i)' > <h3> ";
				echo $pr_title_arr[$i];
				echo " </h3> </td> ";
			}
			echo " </tr> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td  onClick='doclick($i)' style='padding-right:12px' > <img width='300px' src='/article/";
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
				echo " <td> <table> <tr> <td> <input id='cb_$i' type='checkbox' onclick='doclick($i)' > </td> <td> Ord pris <br> ";
				echo " <div style='color:red' > ";
				echo $pr_price_arr[$i];
				echo " </div> ";
				echo " </td> </table> </td> ";
			}

			echo " </tr> <tr> ";

			echo " <td colspan=3 > ";
			echo t(4) . " <a href='$many'> <button class='shake_green_sml' > Kompisrabatt </button> </a> ";
			echo " </td> </tr> <tr> ";

			echo " <td colspan=3 > ";

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

			$lnk_t = get_styr($styr, 'prod', "link.text", $variant);
			$lnk_u = get_styr($styr, 'prod', "link.url", $variant);
			if (strpos($lnk_u, '?'))
				$lnk_u .= "&id=" . $lid;
			else
				$lnk_u .= "?id=" . $lid;
			$lnk_u .= "&prod=" . $pid;

			$many = "04b-many.php?lid=" . $lid;
			//echo " <a href='$many'> Best&auml;ll flera </a> <br> <br> \n";

			echo " <a href='$lnk_u'> <button class='shake_green' > $lnk_t </button> </a> ";
			echo " </td> </tr> </table> ";

		?>

		<br /> <br /> 

	</div>

	<div style="display:none" >
		<img id='circImg' src='../red-circle.png' onload='on_update_2()' /> 
	</div>
	<div style="display:none" >
		<?php echo "<img id='priceImg' src='../pris.jpg' onload='on_update_3(" . $pr_price . ")' /> \n" ?>
	</div>

</body>
 
</html>
