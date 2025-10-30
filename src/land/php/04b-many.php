
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

//debug_log($pr_mr);

$rebate = json_decode($pr_mr);

//debug_log(print_r($rebate,true));

$rebatestr = "{";
foreach ($rebate as $k=>$v)
{
	$rebatestr .= "'" . $k . "'" . ":" . $v . ",";
}
$rebatestr .= "}";

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

$pak_sub_tot = 0;
$i = 0; $n = count($subs);
for ($i=0; $i<$n; ++$i) {
	$pak_sub_tot += $pr_price_arr[$i];
}

$pak_reb = $pak_sub_tot - $pr_price;

$lnk_t = get_styr($styr, 'prod', "link.text", $variant);
$lnk_u = get_styr($styr, 'prod', "link.url", $variant);
$lnk_u = addKV($lnk_u, "id", $lid);
$lnk_u = addKV($lnk_u, "prod", $pid);



?>

<!DOCTYPE html>

<html>

<head>

	<title> <?php echo get_styr($styr,"common","title",$variant); ?> </title>

	<link rel="stylesheet" href="../main-v03.css" />
	<link rel="icon" href="../../site/common/favicon.ico" />
 
	<?php include "00-style.php"; ?>

	<script>

	function on_update_all() { upd_cnt(); }

	function nice_price(p)
	{
		p = Math.floor(p);
		s = "";

		//sp = "&thinsp;";
		sp = "&#8239;"; // narrow no-break space

		if (p>=1000000)
		{
			t = Math.floor(p/1000000);
			s += t.toString() + sp;
			p -= t*1000000;
		}

		if (p>=1000)
		{
			t = Math.floor(p/1000);

			ss = t.toString();
			if (s.length > 0)
				while (ss.length < 3)
					ss = "0" + ss;
			s += ss + sp;
			p -= t*1000;
		}

		if (p>=0)
		{
			ss = p.toString();
			if (s.length > 0)
				while (ss.length < 3)
					ss = "0" + ss;
			s += ss;
		}

		return s + ":-";
	}

	function upd_cnt()
	{
		arr = <?php echo $rebatestr; ?> ;

		pkp = <?php echo $pr_price; ?> ;
		pkr = <?php echo $pak_reb; ?> ;

		base = <?php echo "'" . $lnk_u . "'"; ?> ;

		var qt = document.getElementById("quantity").value;

		var sb = document.getElementById("changeblub");

		//sb.innerHTML = qt.value.toString();

		var rr = 0;
		var mx = 0;

		for (let key in arr) {
			k = parseInt(key);
			v = arr[key];
			if ( (qt >= k) && (k > mx) )
			{
				rr = v;
				mx = k;
			}
		}

		ss = "paket rabatt " + nice_price(pkr) + " per paket <br> \n";

		ss += qt.toString() + " styck med " + rr.toString() + "% rabatt <br> \n";

		var maxp = qt * (pkp+pkr);
		var spfr = qt * pkp;
		var sper = spfr * (100-rr)/100;

		ss += "slutpris " + nice_price(sper) + " <br> \n";

		ss += "du sparar totalt " + nice_price(maxp-sper) + " <br> \n";

		sb.innerHTML = ss;

		var blnk = document.getElementById("blnk");
		blnk.href = base + "&qtt=" + qt;


		ss = qt.toString() + " styck f&ouml;r totalt " + nice_price(sper) + " <br> \n";
		ss += "Du sparar totalt " + nice_price(maxp-sper);

		var btn = document.getElementById("btn");
		btn.innerHTML = ss;

	}

	</script>

</head>

<body>
	<div>
		<br /> 
		<img width=50% src="../../site/common/logo.png" /> <br />
		<br /> <br />
		
		<hr />
		<h3> Best&auml;ll flera, f&aring; rabatt </h3> <br />
		<label for="quantity"> Antal: </label>
		<input onchange="upd_cnt()" value="5" type="number" id="quantity" name="quantity" min="1" > 
		<hr />
		<div id="changeblub"> </div>
		<hr />

		<?php

			$i = 0; $n = count($subs);
			echo " <br> \n";
			echo " <table> <tr> ";
			for ($i=0; $i<$n; ++$i) {
				echo " <td> <h3> ";
				echo ($i+1) . ". " . $pr_title_arr[$i];
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

			echo " <a id='blnk' href='$lnk_u'> <button id='btn' class='shake_green' > $lnk_t </button> </a> ";
			echo " </td> </tr> </table> ";

		?>

		<br /> <br /> 

	</div>

	<div style="display:none" >
		<img id='circImg' src='../red-circle.png' onload='on_update_all()' /> 
	</div>
	<div style="display:none" >
		<?php echo "<img id='priceImg' src='../pris.jpg' onload='on_update_all()' /> \n" ?>
	</div>

</body>
 
</html>
