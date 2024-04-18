

<?php

include "00-common.php";
include "00-connect.php";

$styr = LoadIni("../styr.txt");

$lid      = getparam('lid');
$variant  = 0;
$cid      = 0;

$query = "SELECT * FROM data WHERE type=17 AND value_a='$lid'";
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res))
{
	$variant = $row['value_b'];
}
$query = "SELECT * FROM data WHERE type=71 AND pers=0 AND value_a=$variant";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res)) {
	$cid = $row['value_b'];
}

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
			<img width=50% src="../../site/common/logo.png" /> <br />
			<div>
				<br /> <br />

				<?php
					$email = getparam("email");
					$it = get_styr($styr, "intro", "text", $variant);
					echo repl($it, "%email%", $email) . "\n";
				?>

				<br /> <br />
				<?php echo "<a href='04-tratten.php?lid=$lid'>" . "\n"; ?>
					<button>
						<?php echo get_styr($styr,"intro","button",$variant); ?>
					</button>
				</a>
			</div>
		</div>
	</body>

</html>



<?php




?> 

