
<?php

include "00-common.php";
include "00-connect.php";

function set_suv_val($i, $val, $lid)
{
	global $emperator;
	$query = "INSERT INTO data (pers, type, value_a, value_b, surv) "
		. "VALUES ('0', '56', '" . $i . "', '" . $val . "', '" . $lid . "');";

	$res = mysqli_query( $emperator, $query );
	return boolval($res);
}


$lid    = getparam('lid');
$max    = 0; // getparam('val');

$styr = LoadIni("../styr.txt");

?> 

<!DOCTYPE html>

<html>
	<head>

		<title> <?php echo $styr['common']['title']; ?> </title>

		<link rel="stylesheet" href="../main-v03.css" />
		<link rel="icon" href="../../site/common/favicon.ico" />
		
		<style>

			div {
				font-family:    roboto;
			}

			td {
				border-spacing:18px;
			}

			img.lite {
				opacity: 0.5;
			}
		
			.shake_green {
				animation: shake 1.82s cubic-bezier(.36, .07, .19, .97) both infinite;
				transform: rotate(0);
				backface-visibility: hidden;
				perspective: 1000px;
				background-color: #66d40e;
				color: white;
				text-shadow: 0 4px 4px #000;
				border-radius: 12px;
				padding: 15px 32px;
				text-align: center;
				font-size: 22px;
				margin: 22px 12px;
				float: center;
				width: 850px;
			}

			.shake_green:hover {
				animation: none;
				border-style: outset;
				text-shadow: 0 4px 4px #333;
			}


			@keyframes shake {
				10%,
				90% {
					transform: rotate(-0.25deg);
				}
				20%,
				80% {
					transform: rotate(0.5deg);
				}
				30%,
				50%,
				70% {
					transform: rotate(-1deg);
				}
				40%,
				60% {
					transform: rotate(1deg);
				}
			}
			
			.last {
				font-family : <?php echo $styr['result']['last.font.family'] . ";" ?>
				font-size : <?php echo $styr['result']['last.font.size'] . ";" ?>
			}
			
		</style>

		<?php
			$kn = $styr['querys']['kat'];

			$kv = [];
			$km = [];

			for ($i = 1; $i <= $kn; ++$i)
			{
				$kv[$i] = 0;
				$km[$i] = 0;
			}

			$nn = $styr['querys']['num'];

			for ($i = 1; $i <= $nn; ++$i)
			{
				$v = getparam('q' . $i);

				$a = $styr['querys'];
				$q = "query.$i.reverse";

				if (array_key_exists($q, $a))
					if ($a[$q]=='true')
						$v = 100-$v;

				$k = $styr['querys']["query.$i.kat"];
				$w = $styr['querys']["query.$i.weight"];

				$kv[$k] += $w * $v;
				$km[$k] += $w * 100;
			}

			$max_name = " &lt;ingen uppgift&gt; ";

			$max = 0;
			$kp = [];
			for ($i = 1; $i <= $kn; ++$i)
			{
				$val = 100.0 * $kv[$i] / $km[$i];
				$kp[$i] = $val;
				if ($val > $max) {
					$max = $val;
					$max_name = $styr['querys']["kat.$i.name"];
				}
			}
		?>

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
					$pkv = "0"; $tim = "ingen upgift";
					$query = "SELECT * FROM data WHERE type=50 AND pers=0";
					$res = mysqli_query( $emperator, $query );
					if ($res) if ($row = mysqli_fetch_array($res)) {
						$pkv = $row['value_a'];
						$t = $row['value_b'];
						$tt = $row['date'];
						$dt = date_create_from_format("Y-m-d H:i:s",$tt);
						date_add($dt, date_interval_create_from_date_string($t . " days"));
						$ttt = date_format($dt, "Y-m-d H:i:s");
						//$now = date_create('now');
						//$dd = date_diff($now, $dt);
						//$tim = date_interval_format($dd, "%d dagar %h timmar %i minuter %s sekunder") . " ";
						//$tim = date_interval_format($dd, "%d dagar");
					}
					echo "var txt = " . "'" . $pkv . " platser';\n";
					echo "var dt = new Date('" . $ttt . "').getTime();\n";
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

			function on_update()
			{
				var val = <?php echo $max; ?> ;
				var img = document.getElementById("tratt");
				var cnv = document.getElementById('myCanvas');
				var ctx = cnv.getContext("2d");
				var w = img.naturalWidth;
				var h = img.naturalHeight;

				cnv.width = w;
				cnv.height = h;
				ctx.drawImage(img, 0, 0);

				var x,y;

				<?php

					$dbg = "";

					for ($i = 1; $i <= $kn; ++$i)
					{
						$p = $kp[$i];
						$c = "'#" . $styr['querys']["kat.$i.color"] . "'";

						$rn = $styr['result']['num'];
						$lim = 1;
						$lo = $up = 0;
						while (true)
						{
							if ($lim == $rn) break;
							$lo = $up;
							$up = $styr['result']["limit.$lim.value"];
							if ($p<=$up) break;
							++$lim;
						}
						$pp = ($p-$lo) / ($up-$lo);
						$y1 = $styr['result']["limit.$lim.top"];
						$y2 = $styr['result']["limit.$lim.bot"];
						$y = $y1 + $pp*($y2-$y1);

						$dbg .= "kat $i (" . $styr['querys']["kat.$i.name"] . ") at $p % (lim $lim) mapped at $y <br>\n";

						echo "				x = w / 2 - w * ($kn-1) / 50 + w * $i / 25; \n";
						echo "				y = $y; \n";
						echo "				ctx.beginPath(); \n";
						echo "				ctx.fillStyle = $c ; \n";
						echo "				ctx.arc(x, y, 11, 0, 2 * Math.PI); \n";
						echo "				ctx.fill(); \n";
					}

				?>

			}

		</script>

	</head>

	<body onload="on_update()" >
		<div>
			<br /> 
			<img width=50% src="../../site/common/logo.png" /> <br />
			<div>
				<br /> <br />

				<?php

					echo $styr['summary']['text'];
					echo "<table>";
					for ($i = 1; $i <= $kn; ++$i)
					{
						echo "<tr style='height:22px;'>";
						$val = 100.0 * $kv[$i] / $km[$i];
						set_suv_val($i, $val, $lid);
						$c = "#" . $styr['querys']["kat.$i.color"];
						echo "<td> <font color='$c'> " . "⬤" . " </font>";
						echo " </td>\n";
						echo "<td>";
						echo $styr['querys']["kat.$i.name"];
						echo "</td><td>";
						echo round($val) . "%";

						if ( $val > $styr['summary']['warn.lim'] )
							echo " <img src='../" . $styr['summary']['warn.img'] . "' /> ";

						echo "</td></tr>";
					}
					echo "</table>" . "\n";
				?>

				<br><hr><br>

				<canvas id="myCanvas" > </canvas>
				<br />

				<br />

				<?php
					echo "<table> <tr> <td> ";
					echo "Det som stressar dig mest är $max_name <br>\n";
					$text = "";
					$n = $styr['result']['num'];
					$si = 1;
					for (; $si<$n; ++$si) {
						$v = $styr['result']["limit.$si.value"];
						if ($max < $v) break;
					}
					$text = $styr['result']["limit.$si.text"];

					$pid = $styr['result']["limit.$si.prod"];
					$pr_title = '';
					$pr_desc = '';
					$pr_price = 0;
					$pr_img = '';
					$query = "SELECT * FROM prod WHERE prod_id=" . $pid;
					$res = mysqli_query( $emperator, $query );
					if ($res) if ($row = mysqli_fetch_array($res)) {
						$pr_title = $row['name'];
						$pr_desc  = $row['pdesc'];
						$pr_price = $row['price'];
						$pr_img   = $row['image'];
						$pr_unl   = $row['unlocks'];
					}

					echo $text;
					echo " <br> \n";

					echo " </td> <td> ";
					echo " &nbsp;&nbsp; ";

					echo " </td> <td> ";
					echo " <canvas id='priceCanv' width='140' height='140' > </canvas> ";
					echo " </td> </tr> </table> ";

					echo " <p> <h1> Ditt personliga erbjudande: En kurs för dig i flera steg. Nu endast " . $pr_price . ":- </h1> </p> \n";

					$subs = explode(",", $pr_unl);

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
						echo " <td> <h3> ";
						echo $pr_title_arr[$i];
						echo " </h3> </td> ";
					}
					echo " </tr> <tr> ";
					for ($i=0; $i<$n; ++$i) {
						echo " <td style='padding-right:12px' > <img width='300px' src='/article/";
						echo $pr_img_arr[$i];
						echo "' > </td> ";
					}
					echo " </tr> <tr> ";
					for ($i=0; $i<$n; ++$i) {
						echo " <td style='padding-right:12px' > ";
						echo str_replace("\r\n", " <br> ", $pr_desc_arr[$i]);
						//echo $pr_desc_arr[$i];
						echo " <br> <br> </td> ";
					}

					echo " </tr> <tr> ";
					for ($i=0; $i<$n; ++$i) {
						echo " <td> Ord pris <br> ";
						echo " <div style='color:red' > ";
						echo $pr_price_arr[$i];
						echo " </div> ";
						echo " </td> ";
					}

					echo " </tr> <tr> ";
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

					//echo "<table> ";
					//echo " <tr> ";
					//echo " <td> &nbsp;&nbsp; </td> ";
					//echo " <td> <canvas id='circCanv' width='384' height='384' > </canvas> </td> ";
					//echo " <td> &nbsp;&nbsp; </td> ";
					//echo " <td> <canvas id='timeCanv' width='384' height='384' > </canvas> </td> ";
					//echo " <td> &nbsp;&nbsp; </td> ";
					////echo " <td> <canvas id='prisCanv2' width='140' height='140' > </canvas> </td> ";
					//echo " </tr> </table> ";

					//echo " <tr> <td colspan=3 > ";
					//echo " <h1 style='text-align:center' > " . $pr_title . " </h1> ";
					//echo " </td> </tr> ";
					//echo " <tr> <td> ";
					//echo " <pre>" . $pr_desc . "</pre> </td> <td> ";
					//echo " <canvas id='circCanv' width='384' height='384' > </canvas> </td> ";
					//echo " <td> <canvas id='timeCanv' width='384' height='384' > </canvas> </td> </tr> <tr> <td> ";
					//echo " <img width='50%' src='/article/" . $pr_img . "' /> </td> <td> ";
					//echo " <canvas id='priceCanv' width='320' height='320' > </canvas> </td><td> ";
					//echo "  </td><td> ";

					$img = $styr['result']['img'];
					$lnk_t = $styr['result']["limit.$si.link.text"];
					$lnk_u = $styr['result']["limit.$si.link.url"];
					if (strpos($lnk_u, '?'))
						$lnk_u .= "&id=" . $lid;
					else
						$lnk_u .= "?id=" . $lid;
					$lnk_u .= "&prod=" . $pid;

					echo " <a href='$lnk_u'> <button class='shake_green' > $lnk_t </button> </a> ";
					//echo </td> </tr> </table> ";

					//echo "<div style='float:right; margin:50px' > \n";
					//echo "<canvas id='circCanv' width='384' height='384' > </canvas> <br> <br> \n";
					//echo "</div> \n";
					//
					//echo "<div style='clear: right' > \n";
					//
					//echo "<br> <a href='$lnk_u'> <button class='shake_green' > $lnk_t </button> </a> <br> \n";

					echo "</div> \n";

				?>
				
				<br /> <br /> <br />
				
				<?php echo "<p class='last'> " . $styr["result"]["last.text"] . "</p> \n"; ?>

				<?php echo "<p class='last'> <a href='" . $styr["result"]["last.link"] . "'> " . $styr["result"]["last.name"] . " </a> </p> \n"; ?>

				<br /> <br /> <br />

				<div style="display:none" >
					<?php echo "<img id='tratt' src='../$img' onload='on_update()' /> \n"; ?>
				</div>
				<div style="display:none" >
					<img id='circImg' src='../red-circle-free-png-2.png' onload='on_update_2()' /> 
				</div>
				<div style="display:none" >
					<?php echo "<img id='priceImg' src='../pris.jpg' onload='on_update_3(" . $pr_price . ")' /> \n" ?>
				</div>

			</div>
		</div>
		<?php
			if (getparam('debug')=='true') {
				echo "<div> " . $dbg . "</div>";
			}
		?>
	</body>

</html>

