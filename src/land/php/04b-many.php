
<?php

include "00-common.php";
include "00-connect.php";

include_once "../../site/common/debug.php";

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

$bid = $variant;

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
 
	<?php include "00-style.php"; ?>

	<script>



	</script>

</head>

<body>
	<div>
		<br /> 
		<img width=50% src="../../site/common/logo.png" /> <br />
		<br /> <br /> 

		<?php

			$text = "";

			$pid = get_styr($styr, 'prod', 'prod.num', $variant);

			$pr_title = '';
			$pr_desc = '';
			$pr_price = 0;
			$pr_img = '';
			$query = "SELECT * FROM prod WHERE prod_id=" . $pid;

			$res = mysqli_query( $emperator, $query );
			if ($res) if ($row = mysqli_fetch_array($res)) {
				$pr_title = $row['name'];
				$pr_desc  = $row['pdesc'];
				$pr_price = $row['price'];
				$pr_img   = $row['image'];
				$pr_unl   = $row['unlocks'];
				$pr_mr    = $row['MR'];
			}

			$subs = explode(",", $pr_unl);

			$pr_title_arr = [];
			$pr_desc_arr = [];
			$pr_price_arr = [];
			$pr_img_arr = [];

			foreach ($subs as $k=>$v) {
				$query = "SELECT * FROM prod WHERE prod_id=" . $v;
				$res = mysqli_query( $emperator, $query );
				if ($res) if ($row = mysqli_fetch_array($res)) {
					$pr_title_arr[] = $row['name'];
					$pr_desc_arr[]  = $row['pdesc'];
					$pr_price_arr[] = $row['price'];
					$pr_img_arr[]   = $row['image'];
				}
			}

			$i = 0; $n = count($subs);
			echo " <br> \n";
			echo " <table> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td> <h3> ";
				echo $pr_title_arr[$i];
				echo " </h3> </td> ";
			}
			echo " </tr> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td style='padding-right:12px' > <img width='300px' src='/article/";
				echo $pr_img_arr[$i];
				echo "' > </td> ";
			}
			echo " </tr> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td style='padding-right:12px' > ";
				echo str_replace("\r\n", " <br> ", $pr_desc_arr[$i]);
				echo " <br> <br> </td> ";
			}

			echo " </tr> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td> Ord pris <br> ";
				echo " <div style='color:red' > ";
				echo $pr_price_arr[$i];
				echo " </div> ";
				echo " </td> ";
			}

			echo " </tr> </table> <br> \n";

			$lnk_t = get_styr($styr, 'prod', "link.text", $variant);
			$lnk_u = get_styr($styr, 'prod', "link.url", $variant);
			if (strpos($lnk_u, '?'))
				$lnk_u .= "&id=" . $lid;
			else
				$lnk_u .= "?id=" . $lid;
			$lnk_u .= "&prod=" . $pid;

			echo " <a href='$lnk_u'> <button class='shake_green' > $lnk_t </button> </a> ";
			echo " </td> </tr> </table> ";

		?>

		<br /> <br /> 

	</div>

	<div style="display:none" >
		<img id='circImg' src='../red-circle.png' onload='on_update_2()' /> 
	</div>
	<div style="display:none" >
		<?php echo "<img id='priceImg' src='../pris.jpg' onload='on_update_3(" . $pr_price . ")' /> \n" ?>
	</div>

</body>
 
</html>
