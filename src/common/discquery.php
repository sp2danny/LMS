
<?php

	function my_mb_ucfirst($str) {
		$fc = mb_strtoupper(mb_substr($str, 0, 1));
		return $fc.mb_substr($str, 1);
	}

	function pr_bef()
	{
		return "<h3><center>Välj den egenskap som ligger närmast dig</center></h3> <br> \n " .
			'<br><table>' . "\n";
	}
	function pr_row($qs, $seg, $i)
	{
		$s = "<tr><td width='275px'><center>";
		$v1 = $qs[$seg][$i][0];
		$v2 = $qs[$seg][$i][1];
		$s .= $v1;
		$s .= "</center></td><td> eller </td><td width='275px'><center>";
		$s .= $v2;
		$s .= '</center></td><td>' . "\n";
		$s .= '<select form="disc" id="' . $seg . $i . '" name="' . $seg . $i . '">' . "\n";
		$s .= '<option value="0">' .  ' &nbsp;&nbsp;&nbsp;&nbsp; ----- v&auml;lj ----- ' ;
		for( $jj = 0 ; $jj < 18 ; ++$jj ) $s .= '&nbsp;';
		$s .= '</option>' . "\n";
		$s .= '<option value="-1"> &nbsp; ' . $v1 . ' </option>' . "\n";
		$s .= '<option value="+1"> &nbsp; ' . $v2 . ' </option>' . "\n";
		$s .= '</select></td></tr>' . "\n";
		return $s;
	}
	function pr_aft()
	{
		return '</table>' . "\n";
	}

function dodisc($pnr)
{

	$disc = fopen("../common/disc.txt", "r") or die("Unable to open file!");

	$seg = '';
	$qs = [];

	while (true) {
		$buffer = fgets($disc, 4096); // or break;
		if (!$buffer) break;
		$str = trim($buffer);
		$n = strlen($str);
		if (!$n) continue;
		if ($str[0] == '#') continue;
		if (($str[0] == '[') && ($str[$n-1] == ']')) {
			$seg = substr($str, 1, $n-2);
			continue;
		}
		$qs[$seg][] = str_getcsv($str, ",");
	}

	$ret = '<form action="../common/post_disc.php" id="disc" method="get" >' . "\n" ;

	$ret .= '<div id="idtag" style="display: none;">' ;
	$ret .= '<input type="text" name="pnr" value="';
	$ret .= $pnr;
	$ret .= '"> </div>' . "\n";

	$ret .= pr_bef();
	$n = count($qs["UD"]);
	for ($i=0; $i<$n; ++$i) {
		$ret .= pr_row($qs, "UD", $i);
	}
	$ret .= pr_aft();

	$ret .= "<br><br><br>";

	$ret .= pr_bef();
	$n = count($qs["LR"]);
	for ($i=0; $i<$n; ++$i) {
		$ret .= pr_row($qs, "LR", $i);
	}
	$ret .= pr_aft();

	$ret .= "<br> Slutförd &nbsp; &nbsp; <code>" . date("Y M d") . " </code> <br>\n" ;

	$ret .= '<input type="submit" value="Klar"></form></body></html>';

	return $ret;
}

?>
