

<?php

include "00-common.php";
include "00-connect.php";

$styr = LoadIni("../styr.txt");

$variant  = 1;
$pnr      = getparam('pnr', false);
$pid      = getparam('pid', false);

if ($pnr) {
	$query = "SELECT * FROM pers WHERE pnr='$pnr'";
	$res = mysqli_query($emperator, $query);
	if ($res) if ($row = mysqli_fetch_array($res))
	{
		$pid = $row['pers_id'];
	}
}
else
if ($pid) {
	$query = "SELECT * FROM pers WHERE pers_id='$pid'";
	$res = mysqli_query($emperator, $query);
	if ($res) if ($row = mysqli_fetch_array($res))
	{
		$pnr = $row['pnr'];
	}
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
		<?php echo "font-family: " . get_styr($styr, "querys", "preamble.font.family", $variant) . ";\n" ?>
		<?php echo "font-size: " . get_styr($styr, "querys", "preamble.font.size", $variant) . ";\n" ?>
	}
	p.postamble {
		<?php echo "font-family: " . get_styr($styr, "querys", "postamble.font.family", $variant) . ";\n" ?>
		<?php echo "font-size: " . get_styr($styr, "querys", "postamble.font.size", $variant) . ";\n" ?>
	}
	
  </style>

  <script>

    function range_slider_change(idx)
    {
      inp = document.getElementById( "q" + idx );
      lbl = document.getElementById( "l" + idx );

      val = inp.value;
      lbl.innerHTML = " &nbsp; " + val + " &nbsp; ";
    }

  </script>

</head>

<body>
	<div>
		<br /> 
		<img width=50% src="../../site/common/logo.png" /> <br />
		<br /> <br /> 
	
		<?php echo "<p class='preamble' > \n" . get_styr($styr, "querys", "preamble.text", $variant) . " </p> \n"; ?>

		<?php echo get_styr($styr, "querys", "instructions", $variant); ?>
		<br /> <br /> 
		<br /> <hr />
		<div>
			<form id='gap' action='05-result.php' >
				<?php if ($pnr) { echo "<input type='hidden' id='pnr' name='pnr' value='" . $pnr . "' />" . $eol; } ?>
				<?php if ($pid) { echo "<input type='hidden' id='pid' name='pid' value='" . $pid . "' />" . $eol; } ?>
				<table>
					<?php
						$num = get_styr($styr, "querys", "num",  $variant);
						$qn  = get_styr($styr, "querys", "not",  $variant);
						$qf  = get_styr($styr, "querys", "full", $variant);

						echo "          <tr>" . $eol;
						echo "            <td></td>" . $eol;
						echo "            <td>" . $eol;
						echo "              $qn" . $eol;
						echo "            </td>" . $eol;
						echo "            <td></td> " . $eol;
						echo "            <td>" . $eol;
						echo "              $qf" . $eol;
						echo "            </td>" . $eol;
						echo "            <td></td> " . $eol;
						echo "          </tr>" . $eol;

						for ($i = 1; $i <= $num; ++$i) {
							$qq = 'q' . $i;
							$rsc = "'range_slider_change($i)'";
							echo "        <tr>" . $eol;
							echo "          <td width='375px' >" . $eol;
							//echo "            <label for='" . $qq . "' >" . $eol;
							echo "              " . get_styr($styr, "querys", "query.$i.text", $variant) . $eol;
							//echo "            </label>" . $eol;
							echo "          </td>" . $eol;
							echo "          <td width='1px' >" . $eol;
							//echo "            $qn" . $eol;
							echo "          </td>" . $eol;
							echo "          <td width='475px' >" . $eol;
							echo "            <div  class='range' style='--step:10; --min:0; --max:100'  >" . $eol;
							echo "              <input type='range' class='inputslider' oninput=".$rsc." onchange=".$rsc." min=0 max=100 step=1 id='".$qq."' name='".$qq."' value='0' /> " . $eol;
							echo "            </div> " . $eol;
							echo "          </td> " . $eol;
							echo "          <td width='45px' > " . $eol;
							echo "             <div id='l$i' > </div> " . $eol;
							echo "          </td> " . $eol;
							echo "          <td width='1px' >" . $eol;
							//echo "            $qf" . $eol;
							echo "          </td>" . $eol;
							echo "        </tr> " . $eol;
						}
					?>
				</table>
				<hr />
		
				<?php echo "<p class='postamble' > \n" . get_styr($styr, "querys", "postamble.text", $variant) . " </p> \n"; ?>

				<input class='shake_green' type='submit' value= <?php echo "'" . get_styr($styr, "querys", "button.text", $variant) . "' />"; ?>
			</form>
			<br /> <br />
		</div>
		<br /> <br /> 

	</div>
</body>
 

</html>

