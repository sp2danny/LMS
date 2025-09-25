

<?php

include "00-common.php";
include "00-connect.php";

$styr = LoadIni("../styr.txt");

$lid      = getparam('lid');
$variant  = 0;
$cid      = 0;
$pnr      = getparam('pnr', false);
$pid      = false;

if ($pnr) {
	$query = "SELECT * FROM pers WHERE pnr='$pnr'";
	$res = mysqli_query($emperator, $query);
	if ($res) if ($row = mysqli_fetch_array($res))
	{
		$pid = $row['pid'];
	}
}

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

$eol = "\n";

?>

<!DOCTYPE html>

<html>

<head>

  <title> <?php echo get_styr($styr,"common","title",$variant); ?> </title>

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

	form {
		border-style : none;
		background-color: #fff;
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
  
    th, td {
      padding-top: 10px;
      padding-bottom: 10px;
	}
	p.preamble {

	}
	p.postamble {

	}
	
  </style>

  <script>



  </script>

</head>

<body>
	<div>
		<br /> 
		<img width=50% src="../../site/common/logo.png" /> <br />
		<br /> <br /> 



		<?php


			$n = get_styr($styr, "land", "num", $variant);
			for ($i = 1; $i<=$n; ++$i)
			{
				$t = get_styr($styr, "land", "text." . $i , $variant);
				echo $t . "\n";
				if ($i < $n)
				{
					echo "<br /> <hr /> \n";
				}
			}

			echo "<a href='https://mind2excellence.se/klarna/php/buy.php?id=4335&prod=3' > \n";

			echo "<button class='shake_green' > " .  get_styr($styr, "land", "knapp" , $variant) . " </button> \n";

			echo "</a> \n";

		?>



		<br /> <br /> 

	</div>
</body>
 

</html>

