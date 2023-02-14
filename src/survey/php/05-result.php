
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

				$k = $styr['querys']['query.' . $i . '.kat'];
				$w = $styr['querys']['query.' . $i . '.weight'];

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
				
					for ($i = 1; $i <= $kn; ++$i)
					{
						$p = $kp[$i];
						$c = "'#" . $styr['querys']['kat.' . $i . '.color'] . "'";
						
						echo "				x = w / 2 - w * ($kn-1) / 50 + w * $i / 25; \n";
						echo "				y = h * (0.1 + $p * 0.008); \n";
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
						echo "<tr>";
						$val = 100.0 * $kv[$i] / $km[$i];
						$c = "#" . $styr['querys']['kat.' . $i . '.color'] . "";
						echo "<td> <font color='$c'> " . "⬤" . " </font> </td>\n";
						echo "<td>";
						echo $styr['querys']["kat.$i.name"];
						echo "</td><td>";
						echo round($val) . "%";
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
					echo "Det som stressar dig mest är " . $max_name . " <br>\n";
					$text = "";
					$n = $styr['result']['num'];
					for ($i=1; $i<=$n; ++$i) {
						$v = $styr['result']["limit.$i.value"];
						if ($max >= $v) {
							$text = $styr['result']["limit.$i.text"];
						}
					}
					echo $text;
					$img = $styr['result']['img'];
				?>

				<br /> <br /> <br />
				
				<div style="display:none" >
					<?php echo "<img id='tratt' src='../$img' onload='on_update()' /> \n"; ?>
				</div>
			</div>
		</div>
	</body>

</html>



<?php




?> 

