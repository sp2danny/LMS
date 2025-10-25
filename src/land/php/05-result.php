
<?php

include_once "00-common.php";
include_once "00-connect.php";
include_once '../../site/common/debug.php';


function set_suv_val($i, $val, $lid)
{
	global $emperator;
	$query = "INSERT INTO data (pers, type, value_a, value_b, surv) "
		. "VALUES ('0', '56', '" . $i . "', '" . $val . "', '" . $lid . "');";
		
	debug_log($query);

	$res = mysqli_query( $emperator, $query );
	return boolval($res);
}

function set_suv_val_pid($type, $i, $val, $pid, $surv)
{
	global $emperator;
	$query = "INSERT INTO data (pers, type, value_a, value_b, surv) "
		. "VALUES ('$pid', '$type', '$i', '$val', '$surv');";
	debug_log($query);

	$res = mysqli_query( $emperator, $query );
	return boolval($res);
}

//surv
//	surv_id   int ai key
//	date      datetime auto
//	name      string
//	type      int
//	pers      int pers_id
//	seq       int

function add_surv($type, $pid, $name = false)
{
	if ($name === false)
		$name = "Survey " . $type;
		
	$maxseq = 0;
	
	global $emperator;

	$query = "SELECT * FROM surv WHERE type='$type' AND pers='$pid';";
	debug_log($query);
	$res = mysqli_query( $emperator, $query );
	if ($res) while ($row = mysqli_fetch_array($res))
	{
		if ($row['seq'] > $maxseq)
			$maxseq = $row['seq'];
	}
	
	$maxseq += 1;
	
	$query = "INSERT INTO surv (name, type, pers, seq) "
		. "VALUES ('$name', '$type', '$pid', '$maxseq');";
	debug_log($query);
	$res = mysqli_query( $emperator, $query );
	
	if ($res)
		return mysqli_insert_id($emperator);
	else
		return false;
	
}

$lid    = getparam('lid');
$max    = 0; // getparam('val');

$styr = LoadIni("../styr.txt");

$bid = 0;
$query = "SELECT * FROM data WHERE type=17 AND value_a='$lid'";
$result = mysqli_query($emperator, $query);
if ($result) if ($row = mysqli_fetch_array($result))
{
	$bid = $row['value_b'];
}

$pkv = "0";
$query = "SELECT * FROM data WHERE type=50 AND pers=0";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$pkv = $row['value_a'];
	$dagar = $row['value_b'];
	$startd = $row['date'];
}

//	namn        type         a            b            c            surv
//	==========  ===========  ===========  ===========  ===========  ===========
//	channel     70           platser      dagar        namn
//	variant     71           variantnmr   channel-id   kommentar

$query = "SELECT * FROM data WHERE type=71 AND pers=0 AND value_a=$bid";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$cid = $row['value_b'];
}
$query = "SELECT * FROM data WHERE type=70 AND pers=0 AND data_id=$cid";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$pkv = $row['value_a'];
	$dagar = $row['value_b'];
	$startd = $row['date'];
}

$dt = date_create_from_format("Y-m-d H:i:s",$startd);
date_add($dt, date_interval_create_from_date_string($dagar . " days"));
$ttt = date_format($dt, "Y-m-d H:i:s");

$variant = 1;

?> 

<!DOCTYPE html>

<html>
	<head>

		<title> <?php echo get_styr($styr, 'common', 'title', $variant); ?> </title>

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
				font-family : <?php echo get_styr($styr, 'result', 'last.font.family', $variant) . ";" ?>
				font-size : <?php echo get_styr($styr, 'result', 'last.font.size', $variant) . ";" ?>
			}
			
			.blurb {
				font-family : <?php echo get_styr($styr, 'result', 'blurb.font.family', $variant) . ";" ?>
				font-size : <?php echo get_styr($styr, 'result', 'blurb.font.size', $variant) . ";" ?>
			}
			
			.pitch {
				font-family : <?php echo get_styr($styr, 'result', 'pitch.font.family', $variant) . ";" ?>
				font-size : <?php echo get_styr($styr, 'result', 'pitch.font.size', $variant) . ";" ?>
			}
			
		</style>

		<?php
			$kn = get_styr($styr, 'querys', 'kat', $variant);

			$kv = [];
			$km = [];

			for ($i = 1; $i <= $kn; ++$i)
			{
				$kv[$i] = 0;
				$km[$i] = 0;
			}

			$nn = get_styr($styr, 'querys', 'num', $variant);

			for ($i = 1; $i <= $nn; ++$i)
			{
				$v = getparam('q' . $i);

				$a = $styr['querys'];
				$q = "query.$i.reverse";

				if (array_key_exists($q, $a))
					if ($a[$q]=='true')
						$v = 100-$v;

				$k = get_styr($styr, 'querys', "query.$i.kat", $variant);
				$w = get_styr($styr, 'querys', "query.$i.weight", $variant);

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
					$max_name = get_styr($styr, 'querys', "kat.$i.name", $variant);
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
						$c = "'#" . get_styr($styr, 'querys', "kat.$i.color", $variant) . "'";

						$rn = get_styr($styr, 'result', 'num', $variant);
						$lim = 1;
						$lo = $up = 0;
						while (true)
						{
							if ($lim == $rn) break;
							$lo = $up;
							$up = get_styr($styr, 'result', "limit.$lim.value", $variant);
							if ($p<=$up) break;
							++$lim;
						}
						$pp = ($p-$lo) / ($up-$lo);
						$y1 = get_styr($styr, 'result', "limit.$lim.top", $variant);
						$y2 = get_styr($styr, 'result', "limit.$lim.bot", $variant);
						$y = $y1 + $pp*($y2-$y1);

						$dbg .= "kat $i (" . get_styr($styr, 'querys', "kat.$i.name", $variant) . ") at $p % (lim $lim) mapped at $y <br>\n";

						echo t(4) . "x = w / 2 - w * ($kn-1) / 50 + w * $i / 25; \n";
						echo t(4) . "y = $y; \n";
						echo t(4) . "ctx.beginPath(); \n";
						echo t(4) . "ctx.fillStyle = $c ; \n";
						echo t(4) . "ctx.arc(x, y, 11, 0, 2 * Math.PI); \n";
						echo t(4) . "ctx.fill(); \n";
					}

				?>

			}

			function do_download()
			{
				fetch( <?php echo "'00-update.php?num=57&bid=$bid'"; ?> );

				var link=document.createElement('a');
				link.href = <?php echo "'" . get_styr($styr, "result", "last.link", $variant) . "';"; ?>
				link.download = <?php echo "'" . get_styr($styr, "result", "last.nice", $variant) . "';"; ?>
				link.click();
				return false;
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
				
					$pnr = getparam('pnr');
					$pid = getparam('pid');
					if ($pnr && ! $pid) {
						$query = "SELECT * FROM pers WHERE pnr='$pnr'";
						$res = mysqli_query( $emperator, $query );
						if ($res) if ($row = mysqli_fetch_array($res)) {
							$pid = $row['pers_id'];
						}
					}
					
					debug_log("pnr & pid = " . $pnr . "," . $pid);

					$surv = false;
					if ($pid) {
						$surv = add_surv(101, $pid, "Stress");
					}
					debug_log("surv = " . $surv);

					echo get_styr($styr, 'summary', 'text', $variant) . "\n";
					echo t(4) . "<table>\n";
					for ($i = 1; $i <= $kn; ++$i)
					{
						echo t(5) . "<tr style='height:22px;'>";
						$val = 100.0 * $kv[$i] / $km[$i];
						set_suv_val($i, $val, $lid);
						if ($surv) {
							set_suv_val_pid(101, $i, $val, $pid, $surv);
						}
						$c = "#" . get_styr($styr, 'querys', "kat.$i.color", $variant);
						echo "<td> <font color='$c'> " . "⬤" . " </font>";
						echo " </td> <td> ";
						echo get_styr($styr, 'querys', "kat.$i.name", $variant);
						echo "</td><td>";
						echo round($val) . "%";

                        if (array_key_exists('warn.rev',$styr['summary']) && $styr['summary']['warn.rev']) {
    						if ( $val < $styr['summary']['warn.lim'] ) {
	    						echo " <img src='../" . $styr['summary']['warn.img'] . "' /> ";
    						}
						} else {
						    if ( $val > $styr['summary']['warn.lim'] ) {
    							echo " <img src='../" . $styr['summary']['warn.img'] . "' /> ";
						    }
                        }

						echo "</td></tr>\n";
					}
					echo t(4) . "</table>" . "\n";
				?>

				<br><hr><br>

				<canvas id="myCanvas" > </canvas>
				<br />

				<br />

				<?php
					echo "<table> <tr> <td> \n";
					echo t(4) . "<p class='blurb'> \n";

					echo t(4) . "Det som stressar dig mest $max_name <br>\n";
					$text = "";
					$n = get_styr($styr, 'result', 'num', $variant);
					$si = 1;
					for (; $si<$n; ++$si) {
						$v = get_styr($styr, 'result', "limit.$si.value", $variant);
						if ($max < $v) break;
					}
					$text = get_styr($styr, 'result', "limit.$si.text", $variant);

					$pid = get_styr($styr, 'result', "limit.$si.prod", $variant);
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

					$img = get_styr($styr, 'result', 'img', $variant);
					$lnk_t = get_styr($styr, 'result', "limit.$si.link.text", $variant);
					$lnk_u = get_styr($styr, 'result', "limit.$si.link.url", $variant);
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

					//echo "</div> \n";

				?>
				
				<br /> <br /> <br />
				
				<?php echo "<p class='last'> " . get_styr($styr, "result", "last.text", $variant) . "</p> \n"; ?>

				<?php echo "<p class='last'>  <a href='javascript:;' onclick='do_download();' download='" . get_styr($styr, "result", "last.nice", $variant). "' > " . get_styr($styr, "result", "last.name", $variant) . " </a> </p> \n"; ?>

				<br />
				<div id='dlarea'> </div>

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

