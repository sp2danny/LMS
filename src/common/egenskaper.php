
<!-- include egenskaper.php -->

<?php

include_once 'head.php';
include_once 'roundup.php';
include_once 'util.php';
include_once 'common_php.php';
include_once 'connect.php';
include_once 'discdisplay.php';

echo <<<EOL
<style>

.main {
	/*font-family: Arial; */
	font-size: 18px;
}

.sml {
	font-size: 11px;
}

.twm {
	padding-left : 15px;
}

.lf {
	width : 170px;
}

.ls {
	width : 210px;
}

.cn {
	text-align : center;
	font-size: 16px;
}

</style>
EOL;

function index()
{
	echo "</head><body class='main'>\n";
	
	global $emperator;
	
	$pnr = getparam("pnr", "0");
	$pid = getparam("pid", "0");

	if ($pnr != 0) {
		$query = "SELECT * FROM pers WHERE pnr='$pnr';";
		$res = mysqli_query($emperator, $query);
		if ($res)
			$prow = mysqli_fetch_array($res);
		$pid = $prow['pers_id'];
	}

	$disc = get_disc($pid);

	echo "<table><tr><td>\n";

	if ($disc)
	{
		echo disc_draw($disc['LR'], $disc['UD']);
	}
	
	echo "</td><td> \n";
	
	echo "<table><tr><td>";
	
echo "<td class='twm' >\n";
echo "Detta är mina styrkor:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 301, $i], 'value_c', '');
	echo "<input class='lf' id='st_$i' readonly type='text' value='$val' /> ";
	echo "</li>\n";
}
echo "</ul>\n<br />\n";

	echo "</td><td>";

echo "<td class='twm' >\n";
echo "Detta är mina svagheter:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 302, $i], 'value_c', '');
	echo "<input class='lf' id='sv_$i' readonly type='text' value='$val' /> ";
	echo "</li>\n";
}

echo "</ul>\n<br />\n";


	echo "</td></tr><tr><td>";

echo "<td class='twm' >\n";
echo "Detta är mina motivatorer:\n<ul>\n";

for ($i=1; $i<=5; ++$i)
{
	echo "\t<li> " . $i . " &nbsp;&nbsp;&nbsp;";
	$val = ROD('data', ['pers', 'type', 'value_a'], [$pid, 303, $i], 'value_c', '');
	echo "<input class='lf' id='mo_$i' readonly type='text' value='$val' /> ";
	echo "</li>\n";
}

echo "</ul>\n<br />\n";


	echo "</td><td>";



	echo "</td></tr></table>";

	
	echo " </td></tr></table>\n";



}




index();

?>

<br><br>

 <img width=100%  src=" allafargtext.png" /> <br />
        <br /> <br /> 
        Skriv ut ovanstående discanalys, stryk under de motivatorer som är de viktigaste för dig, och diskutera i gruppen om andra delar din uppfattning om din beteendeprofil. Sätt sedan in i din handlingsplan. Ta med detta dokument till examinationen på Nivå 1 i nästa steg.
        <br /> <br /> 
        <iframe width="85%" height="850px"   src=" ../common/Disc2014.pdf" /> </iframe> <br />
        <br /> <br /> 
        Skriv också ut och läs igenom ovanstående detaljerade tolkning av olika beteendestilar och behåll i din utbildningspärm. Du kommer att lära dig mer om olika beteendestilar längre fram i utbildningen.<br> (Skriv ut dokunentet genom att först ställa markören på dokumentet och högerklicka. Välj: Öppna med förhandsvisning. Sedan skriv ut.) Sätt in dokumentet i din utbildningspärm.
        <br /> 



</body>
</html>
