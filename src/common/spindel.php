
<!DOCTYPE html>

<html>
	<head>
		
		<meta charset="UTF-8" />
		<title> <?php if(isset($TITLE)) echo $TITLE; else echo "Emperator Cockpit" ?> </title>
		<!-- script src="main.js"></script -->
		<script src="https://www.google.com/jsapi"></script>
		
		<style>
		<?php include("main.css"); ?>
		<!--link rel="stylesheet" type="text/css" href="main.css" /-->
		</style>

		<script> 
		<?php include('main.js.php'); ?>
		</script>

	</head>

	<?php
	$dosmall = false;
	if( array_key_exists( 'small', $_GET ) )
		if($_GET['small'])
			$dosmall = true;
	echo '	<body style="padding-left: ' ;
	if($dosmall)
		echo '11px; padding-right: 13px; font-size: small';
	else
		echo '80px; padding-right: 60px';
	echo ';" >' . "\n" ;
	?>
	<!-- table class="plain">
	<tr height="225px">
	<td class="hortile" width="100%" -->
	

		<div class="wrapper">
			<p>
				<div class="center"> <br /> <?php
				if($dosmall)
					echo '<img src="head_sml.png" />' ;
				else
					echo '<img src="head.png" />' ;
				?> <br /> <br /> </div>
				
					</td>
	<!-- /tr>
	<tr height="100%"><td -->
	
				<div class="center"> <!--big> Emperator Cockpit </big--> <br /> </div>
				<!--div class="center"> <small> Kompetensvalidering </small> <br /> </div-->
				<!--br /><br /-->
				<div id="MainBody">


<?php


// include('connect.inc');

include('main.js.php');


//  ett gap analys

echo "<script>\n\n";
echo "targets = [ 3.00, 3.11, 3.22, 2.56, 2.56, 2.22, 2.78, 2.89, 3.22, 2.67, \n";
echo "            2.33, 2.89, 3.33, 2.67, 3.22, 2.33, 2.89, 2.67, 2.22, 3.22, \n";
echo "            2.44, 2.33, 3.11, 3.22, 2.56, 2.78 ]; \n\n";


echo "targ_s  = [ 4.00 , 3.55 , 4.22 , 3.88 , 3.77 , 3.33 , 3.33 , 4.11 , 4.33 , 4.00 , \n";
echo "            4.00 , 4.00 , 4.11 , 3.22 , 3.44 , 2.11 , 4.22 , 3.66 , 3.22 , 3.88 , \n";
echo "            3.55 , 2.22 , 3.22 , 3.33 , 2.44 , 2.88  ]; \n\n";

echo "val_e = [ 1,2,3,1,2,3,1,2,3,1,  \n";
echo "          1,2,3,1,2,3,1,2,3,1,  \n";
echo "          1,2,3,1,2,3];  \n\n";

echo "val_b = [ 1,2,3,1,2,3,1,2,3,1,  \n";
echo "          1,2,3,1,2,3,1,2,3,1,  \n";
echo "          1,2,3,1,2,3];  \n\n";


echo "short_desc = [ 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',  \n";
echo "               'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',  \n";
echo "               'u', 'v', 'w', 'x', 'y', 'z' ];  \n\n";

/*$querys = array( 1,2,3 ); 

for( $i=27; $i<=49; ++$i ) $querys[] = $i;

echo "val_e = [\n\t ";

$frst = true;
$i = 0;

$ttl = "alla";
if( $_GET['id'] )
{
	$query_0 = "SELECT * FROM " . $MainDB . "_PersonBas WHERE person_id=" . $_GET['id'];
	$result_0 = mysqli_query($emperator,$query_0);
	$row_0 = mysqli_fetch_array($result_0);
	$ttl = $row_0['f_name'] . " " . $row_0['e_name'] ;
}
else if( $_GET['avd'] )
	$ttl = "Avd:" . $_GET['avd'];

foreach ($querys as $q)
{

	$query = "SELECT * FROM " . $MainDB . "_EnkSvar WHERE query_nr=" . $q;
	$result = mysqli_query($emperator,$query);

	$sum = 0;
	$cnt = 0;

	while($row = mysqli_fetch_array($result))
	{
	
		if( $_GET['id'] )
		{
			// person filter nivå
			if( $row['user_id'] != $_GET['id'] ) continue;
		}
		
		if( $_GET['avd'] )
		{
			$query_2 = "SELECT * FROM " . $MainDB . "_PersonAdditional WHERE id=" . $row['user_id'];
			$result_2 = mysqli_query($emperator,$query_2);
			if($row_2 = mysqli_fetch_array($result_2))
			{
				if( $row_2['avd'] !=  $_GET['avd'] ) continue;
			}
		}

		$sum += $row['response'];
		$cnt += 1;
	}
	
	if(!$frst) echo ",";

	echo " " . ($sum / $cnt);

	if( (++$i % 4 ) == 0 )
		echo "\n\t";

	$frst = false;
}

echo "];\n";

echo "val_b = [\n\t ";

$frst = true;
$i = 0;

foreach ($querys as $q)
{
	++$i;

	$query = "SELECT * FROM " . $MainDB . "_EnkSvarUtv WHERE query_nr=" . $i;
	$result = mysqli_query($emperator,$query);

	$sum = 0;
	$cnt = 0;

	while($row = mysqli_fetch_array($result))
	{
	
		if( $_GET['id'] )
		{
			// person filter nivå
			if( $row['user'] != $_GET['id'] ) continue;
		}

		if( $_GET['avd'] )
		{
			$query_2 = "SELECT * FROM " . $MainDB . "_PersonAdditional WHERE id=" . $row['user'];
			$result_2 = mysqli_query($emperator,$query_2);
			if($row_2 = mysqli_fetch_array($result_2))
			{
				if( $row_2['avd'] !=  $_GET['avd'] ) continue;
			}
		}

		$sum += $row['value'];
		$cnt += 1;
	}
	
	if(!$frst) echo ",";

	echo " " . ($sum / $cnt);

	if( ($i % 4 ) == 0 )
		echo "\n\t";

	$frst = false;
}

echo "];\n";

echo "short_desc = [\n\t ";

$frst = true;
$i = 0;

$query = "SELECT * FROM " . $MainDB . "_BatteriEnk WHERE grupp=1 AND kind=1";
$result = mysqli_query($emperator,$query);

while($row = mysqli_fetch_array($result))
{
	if(!$frst) echo ","; $frst = false;
	echo " '" . utf8_encode($row['shortval']) . "'";
	$i += 1;
	if( ($i % 4 ) == 0 )
		echo "\n\t";
}

$query = "SELECT * FROM " . $MainDB . "_BatteriEnk WHERE grupp=2 AND kind=0";
$result = mysqli_query($emperator,$query);

while($row = mysqli_fetch_array($result))
{
	if(!$frst) echo ","; $frst = false;
	echo " '" . utf8_encode($row['query']) . "'";
	$i += 1;
	if( ($i % 4 ) == 0 )
		echo "\n\t";
}

echo "];\n";
*/
echo "</script>\n";

echo "<center> <table> <tr> <td> \n";

echo '<canvas id="SpiderCanvas" width="550" height="630" style="border:1px solid #000000;">' ;
echo ' Din browser st&ouml;der inte canvas </canvas> ' . "\n";

echo " </td> <td> ";

global $KAT;
$KAT = 'prod' ;
//include('knappar.inc');

echo " </td> </tr> </table> </center> \n ";

echo "<br> <div id='spdr'> </div> <br> \n";

echo "<script> DrawSpider('SpiderCanvas', 26, targets, targ_s, val_e, val_b, short_desc, 'spindel' ); </script> \n";

echo "<br><br>\n";

/*if( $_GET['id'] )
{
	include('disc.inc');
}
else if( $_GET['avd'] )
{
	include('grp_disc.inc');
}*/

// mysqli_close($emperator);

//include('wrapper_after.php');

?>



				<!-- img src="progress.png" / -->
				<!-- /td></tr></table -->
				</div>
			</p>
			<div class="push"> </div>
		</div>
		<div class="footer">
			<p> <div class="center"> &copy; www.elitteam.se 2017 </div> </p>
		</div>
	</body>
</html>




