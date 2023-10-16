

<?php

include "00-common.php";

$styr = LoadIni("../styr.txt");

?>

<!DOCTYPE html>

<html>
	<head>

		<title> <?php echo $styr["common"]["title"]; ?> </title>

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
					$email = getparam("email");
					$it = $styr["intro"]["text"];
					echo repl($it, "%email%", $email);
				?>

				<br /> <br />
				<?php
					$lid = getparam("lid");
					$variant = getparam("variant");
					echo "<a href='04-tratten.php?lid=$lid'>" . "\n";
				?>
					<button>
					<?php echo $styr["intro"]["button"]; ?>
					</button>
				</a>
			</div>
		</div>
	</body>

</html>



<?php




?> 

