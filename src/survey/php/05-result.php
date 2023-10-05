
<?php

include "00-common.php";
include "00-connect.php";


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
			.shake_green {
				animation: shake 0.82s cubic-bezier(.36, .07, .19, .97) both infinite;
				transform: rotate(0);
				backface-visibility: hidden;
				perspective: 1000px;
				background-color: #96bf0d;
				color: white;
				text-shadow: 0 4px 4px #000;
				border-radius: 12px;
				padding: 15px 32px;
				text-align: center;
				font-size: 22px;
				margin: 8px 6px;
				float: right;
			}

			.shake_green:hover {
				animation: none;
				border-style: outset;
				text-shadow: 0 4px 4px #333;
			}


			@keyframes shake {
				10%,
				90% {
					transform: rotate(-1deg);
				}
				20%,
				80% {
					transform: rotate(2deg);
				}
				30%,
				50%,
				70% {
					transform: rotate(-4deg);
				}
				40%,
				60% {
					transform: rotate(4deg);
				}
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

			$max_name = "";
					
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
		
			function on_update_2()
			{
				const canvas = document.getElementById("circCanv");
				const ctx = canvas.getContext("2d");
				const img = document.getElementById("circImg");
				ctx.drawImage(img, 0, 0); 
				ctx.font = "42px roboto";
				var txt = 
				<?php 
					$pkv = "0";
					$query = "SELECT * FROM data WHERE type=50 AND pers=0";
					$res = mysqli_query( $emperator, $query );
					if ($res) if ($row = mysqli_fetch_array($res))
						$pkv = $row['value_a'];
					echo "'" . $pkv . " platser';\n";
				?>
				
				var xx = (384 - ctx.measureText(txt).width)/2;
				ctx.fillText(txt, xx, 175);
				txt = "kvar";
				xx = (384 - ctx.measureText(txt).width)/2;
				ctx.fillText(txt, xx, 255);
		
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
			<img width=50%  src="../../site/common/logo.png" /> <br />
			<div>
				<br /> <br />
				
				<?php

					echo $styr['summary']['text'];

					echo "<table>";
					for ($i = 1; $i <= $kn; ++$i)
					{
						echo "<tr style='height:22px;'>";
						$val = 100.0 * $kv[$i] / $km[$i];
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
				
				<canvas id="myCanvas" >
				din browser st&ouml;der inte canvas
				</canvas>
				<br />

				<br />

				<?php
					echo "Det som stressar dig mest är $max_name <br>\n";
					$text = "";
					$n = $styr['result']['num'];
					$i = 1;
					for (; $i<$n; ++$i) {
						$v = $styr['result']["limit.$i.value"];
						if ($max < $v) break;
					}
					$text = $styr['result']["limit.$i.text"];

					echo $text;
					$img = $styr['result']['img'];
					$lnk_t = $styr['result']["limit.$i.link.text"];
					$lnk_u = $styr['result']["limit.$i.link.url"];
					if (strpos($lnk_u, '?'))
						$lnk_u .= "&id=" . $lid;
					else
						$lnk_u .= "?id=" . $lid;
					
					echo "<div style='float:right; margin:50px' > \n";
					echo "<canvas id='circCanv' width='384' height='384' > </canvas> <br> <br> \n";
					echo "</div> \n";
					
					echo "<div style='clear: right' > \n";
					
					echo "<br> <a href='$lnk_u'> <button class='shake_green' > $lnk_t </button> </a> <br> \n";
					echo "</div> \n";

				?>

				<br /> <br /> <br />
				
				<div style="display:none" >
					<?php echo "<img id='tratt' src='../$img' onload='on_update()' /> \n"; ?>
				</div>
				<div style="display:none" >
					<img id='circImg' src='../red-circle-free-png-2.png' onload='on_update_2()' /> 
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



<?php




?> 

