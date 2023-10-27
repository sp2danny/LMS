

<?php

include "00-common.php";

$styr = LoadIni("../styr.txt");

?>

<!DOCTYPE html>

<html>
	<head>

		<title> <?php echo get_styr($styr, "common", "title", $variant); ?> </title>

		<link rel="stylesheet" href="../../site/common/main-v03.css" />
		<link rel="icon" href="../../site/common/favicon.ico" />

		<script>

		</script>

	</head>

	<body>
		<div>
			<br /> 
			<img width=50%  src="../../site/common/logo.png" /> <br />
			<div>
				<br /> <br />

				<?php

					echo get_styr($styr, "register", "greeting", $variant);

				?>
				<br /> <br />
				<form action="02-b-lead_reg.php">
				
					<input type="hidden" id="special" name="special" value="special">
					
					<input type="submit" value="Starta" />

				</form>


			</div>
		</div>
	</body>

</html>



<?php




?> 

