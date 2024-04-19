
<?php

//include_once 'connect.php';
//include_once 'common.php';

include_once "../../survey/php/00-common.php";
include_once "../../survey/php/00-connect.php";

include_once 'debug.php';

function t($n)
{
	$str = "";
	for ($i=0; $i<$n; ++$i)
		$str = $str . "\t";
	return $str;
}

$vals = [];

$sid   = getparam('sid');
$pid   = getparam('pid');
$seq   = getparam('seq');
$st    = getparam('st', 101);
$filt  = getparam('filt', 2);

$ddd = "vals";
$query = "SELECT * FROM data WHERE pers='$pid' AND type='$st' AND surv='$sid';";

debug_log("query : " . $query);

$res = mysqli_query( $emperator, $query );
if ($res) while ($row = mysqli_fetch_array($res))
{
	$val = $row['value_b'];
	$vals[] = $val;
	$ddd .= " " . $val;
}

debug_log($ddd);

$min = LoadIni('min.txt');

$nn = $min['survey']['count'];

$pts = "https://mind2excellence.se/survey/";

for ($ii=1; $ii<=$nn; ++$ii)
{
	// 1.filter = 0
	$ff = $min['survey']["$ii.filter"];
	if ($ff != $filt) continue;
	$pts = $min['survey']["$ii.pts"];
}

debug_log("pts : " . $pts);

$styr = LoadIni("$pts/styr.txt");

$res_img = $styr['result']['img'];

debug_log("res_img : " . $res_img);

$variant = 1;

?> 

<!DOCTYPE html>

<html>
	<head>

		<title> <?php echo get_styr($styr, 'common', 'title', $variant); ?> </title>

		<link rel="stylesheet" href="main-v03.css" />
		<link rel="icon" href="favicon.ico" />

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
				$k = get_styr($styr, 'querys', "query.$i.kat", $variant);
				$w = get_styr($styr, 'querys', "query.$i.weight", $variant);

				$km[$k] += $w * 100;
			}
			
			for ($i = 0; $i < $kn; ++$i)
			{
				$kv[$i+1] += $vals[$i];
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

		</script>

	</head>

	<body onload="on_update()" >
		<div>
			<br /> 
			<img width=50% src="../../site/common/logo.png" /> <br />
			<div>
				<br /> <br />

				<?php
				
					//echo get_styr($styr, 'summary', 'text', $variant) . "\n";
					echo t(4) . "<table>\n";
					for ($i = 1; $i <= $kn; ++$i)
					{
						echo t(5) . "<tr style='height:22px;'>";
						//$val = 100.0 * $kv[$i] / $km[$i];
						$val = $kv[$i];
						$c = "#" . get_styr($styr, 'querys', "kat.$i.color", $variant);
						echo "<td> <font color='$c'> " . "⬤" . " </font>";
						echo " </td> <td> ";
						echo get_styr($styr, 'querys', "kat.$i.name", $variant);
						echo "</td><td>";
						echo round($val) . "%";

						if (array_key_exists('warn.rev',$styr['summary']) && $styr['summary']['warn.rev']) {
    						if ( $val < $styr['summary']['warn.lim'] ) {
	    						echo " <img src='$pts" . $styr['summary']['warn.img'] . "' /> ";
    						}
						} else {
							if ( $val > $styr['summary']['warn.lim'] ) {
    							echo " <img src='$pts" . $styr['summary']['warn.img'] . "' /> ";
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

				<div style="display:none" >
					<?php echo "<img id='tratt' src='$pts/$res_img' onload='on_update()' />\n"; ?>
				</div>

			</div>
		</div>

	</body>

</html>

