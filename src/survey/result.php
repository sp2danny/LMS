
<?php

include "common.php";
include "connect.php";


$lid    = getparam('lid');
$val    = getparam('val');

$styr = LoadIni("styr.txt");

?> 

<!DOCTYPE html>

<html>
	<head>

		<title> Kund Undersökning <?php echo $last_id; ?> </title>

		<link rel="stylesheet" href="./main-v03.css" />
		<link rel="icon" href="../site/common/favicon.ico" />

		<script>

			function on_update()
			{
				var sld = document.getElementById('stress');
				var val = parseInt(sld.value);
				
				var num = document.getElementById('num');
				num.innerHTML = " &nbsp; " + sld.value + " &nbsp; ";
				
				
				var cnv = document.getElementById('myCanvas');
				var ctx = cnv.getContext("2d");

				var img = document.getElementById("tratt");
				ctx.drawImage(img, 0, 0);

				var x = 180;
				var y = 50 + val * 2.3;

				ctx.beginPath();
				ctx.fillStyle = "#000000";
				ctx.arc(x, y, 7, 0, 2 * Math.PI);
				ctx.fill();

			}

		</script>

	</head>

	<body onload="on_update()" >
		<div>
			<br /> 
			<img width=50%  src="../site/common/logo.png" /> <br />
			<div>
				<br /> <br />
				
				<canvas id="myCanvas" width="300" height="400" >
				din browser st&ouml;der inte canvas
				</canvas>
				<br />
				
				<form action="none.php" >
				
					<input type="hidden" id="lid" name="lid" value= <?php echo '"' . $lid . '"'; ?> />

					<?php
					echo '<input type="range" id="stress" name="stress" min="0" max="100" value="';
					echo $val;
					echo ' onchange="on_update()" />' . "\n";
					?>
					<label id="num" for="stress"> Stress </label>
					<br />
					
					<?php
					$text = "";
					$n = $styr['result']['num'];
					for ($i=1; $i<=$n; ++$i) {
						if ($val >= $styr['result']['limit.' . $i . ".value"])
							$text = $styr['result']['limit.' . $i . ".text"];
					}
					echo $text;
					?>
					
					
				</form>

				<br /> <br /> <br />
				
				<div style="display:none" >
					<img id="tratt" src="tratt.png" onload="on_update()" />
				</div>
			</div>
		</div>
	</body>

</html>



<?php




?> 

