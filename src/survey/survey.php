

<!DOCTYPE html>

<html>
	<head>

		<title> Kund Undersökning </title>

		<link rel="stylesheet" href="../site/common/main-v03.css" />
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

				<input type="range" id="stress" name="stress" min="0" max="100" onchange="on_update()" >
				<label id="num" for="stress"> Stress </label>

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

