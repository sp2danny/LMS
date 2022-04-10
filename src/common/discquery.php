<html>
<head></head>
<body>
discquery.php
</body>
</html>



	include_once('wrapper_before.php');

	echo '<!doctype html><html><head><meta charset="ISO-8859-1">' ;

	include('connect.inc');
	$query = "SELECT * FROM " . $MainDB . "_PersonBas WHERE person_id = ";
	$query .= $_GET['id'];
	$result = mysqli_query($emperator,$query);
	echo '<title>Fr&aring;geformul&auml;r';
	if($row = mysqli_fetch_array($result))
	{
		echo ' ' . $row['f_name'] . ' ' . $row['e_name'] ;
	}
	echo '</title></head><body>';

	$query = "SELECT * FROM " . $MainDB . "_BatteriDisc WHERE direction = '1'";

	$result = mysqli_query($emperator,$query);

	function my_mb_ucfirst($str) {
		$fc = mb_strtoupper(mb_substr($str, 0, 1));
		return $fc.mb_substr($str, 1);
	}

	function pr_bef()
	{
		echo "<h3><center>Välj den egenskap som ligger närmast dig</center></h3> <br> \n " ;
		echo '<br><table>' . "\n";
	}
	function pr_row($row,$id)
	{
		echo "<tr><td width='275px'><center>";
		$v1 = htmlentities( my_mb_ucfirst($row['var1']), ENT_COMPAT | ENT_HTML5, "ISO-8859-1" ) ;
		$v2 = htmlentities( my_mb_ucfirst($row['var2']), ENT_COMPAT | ENT_HTML5, "ISO-8859-1" ) ;
		echo $v1;
		echo "</center></td><td> eller </td><td width='275px'><center>";
		echo $v2;
		echo '</center></td><td>' . "\n";
		echo '<select form="disc" id="' . $id . '" name="' . $id . '">' . "\n";
		echo '<option value="0">' .  ' &nbsp;&nbsp;&nbsp;&nbsp; ----- v&auml;lj ----- ' ;
		for( $jj = 0 ; $jj < 18 ; ++$jj ) echo '&nbsp;';
		echo '</option>' . "\n";
		echo '<option value="-1"> &nbsp; ' . $v1 . ' </option>' . "\n";
		echo '<option value="+1"> &nbsp; ' . $v2 . ' </option>' . "\n";
		echo '</select></td></tr>' . "\n";
	}
	function pr_aft()
	{
		echo '</table>' . "\n";
	}

	echo '<form action="post_disc.php" id="disc" method="get" >' . "\n" ;

	$ID = '1';

	echo '<div id="idtag" style="display: none;">' ;
	echo '<input type="text" name="id" value="';
	echo $_GET['id'];
	echo '"> </div>' . "\n";

	pr_bef();
	while($row = mysqli_fetch_array($result))
	{
		pr_row($row,$ID);
		$ID += 1;
	}
	pr_aft();

	//echo '<br><br>' ;

	$query = "SELECT * FROM " . $MainDB . "_BatteriDisc WHERE direction = '2'";

	$result = mysqli_query($emperator,$query);
	
	echo "<br><br><br>";

	pr_bef();
	while($row = mysqli_fetch_array($result))
	{
		pr_row($row,$ID);
		$ID += 1;
	}
	pr_aft();

	echo "<br> Slutförd &nbsp; &nbsp; <code>" . date("Y M d") . " </code> <br>\n" ;

	echo '<input type="submit" value="Klar"></form></body></html>';

	include_once('wrapper_after.php');

?>
