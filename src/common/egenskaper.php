
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

<script>

function clampi(num, min, max) {
  return num <= min 
    ? min 
    : num >= max 
      ? max 
      : Math.round(num);
}

function on_save_click(str)
{
	sb = document.getElementById("ocb");
	sb.disabled = true;
	sb.innerHTML = "Sparad";
	fetch(str);
}

function on_disc_click(event)
{
	on_disc_click.counter = (on_disc_click.counter || 0) + 1;

	if (on_disc_click.counter > 1) return;

	elem = document.getElementById("disc_replace");
	UD = (event.offsetY - 425) / 17.0;
	LR = (event.offsetX - 425) / 17.0;
	UD = clampi(UD, -20, +20);
	LR = clampi(LR, -20, +20);

	oc = 'on_save_click("' + dsname(UD, LR) + '")';
	str = "<button id='ocb' onclick='" + oc + "' > Spara </button> </a> ";
	elem.innerHTML = str;

	var c = document.getElementById("discCanvas");
	var ctx = c.getContext("2d");

	ctx.beginPath();
	ctx.fillStyle = "#337";
	ctx.strokeStyle = "#000";
	ctx.arc( 425+17*LR, 425+17*UD, 8, 0,2*Math.PI);
	ctx.stroke();
	ctx.fill();

}

EOL;

function index()
{
	global $emperator;
	
	$pnr = getparam("pnr", "0");
	$pid = getparam("pid", "0");

	echo "\nfunction dsname(UD, LR) {\n";
	echo "	return 'disk_spara.php?UD=' + UD + '&LR=' + LR";

	if ($pnr != 0) {
		$query = "SELECT * FROM pers WHERE pnr='$pnr';";
		$res = mysqli_query($emperator, $query);
		if ($res)
			$prow = mysqli_fetch_array($res);
		$pid = $prow['pers_id'];
		$nn = $prow['name'];
		echo " + '&by=" . $pid . "'";
	}

	$grpsk = getparam("grpsk", false);

	if ($grpsk !== false)
	{
		$query = "SELECT * FROM pers WHERE pnr='$grpsk';";
		$res = mysqli_query($emperator, $query);
		if ($res)
			$prow = mysqli_fetch_array($res);
		$pid = $prow['pers_id'];
		$nn = $prow['name'];
		echo " + '&for=" . $pid . "'";
	}

	echo ";\n}\n\n</script>\n\n";

	echo "</head><body class='main'>\n";

	$disc = get_disc($pid);

	if ($grpsk) {
		echo "<h5 class='normal' > Skatta $nn's personlighet </h5> \n";
	} else {
		echo "<h5 class='normal' > Testa Din Personlighet </h5> \n";
		$hr = "https://www.mind2excellence.se/site/batt-1.4%20-%20Pulsm%C3%A4tning%202%20-%20DISCpersonlighet,%20GAP%20analys,%20motivation,%20v%C3%A4rdegrunder%20och%20utveckling%20av%20ditt%20personliga%20ledarskap/index.php?seg=1";
		$hr .= '&pid=' . $pid;
		$hr .= '&pnr=' . $pnr;
		$hr .= '&name=' . $nn;
		//$hr .= '&noside=' . 'true';

		//echo " <a href='$hr'> ";
		echo " <button onclick='window.top.location.replace(\"$hr\");' > Gör Testet </button> ";
		//echo " </a> ";
		echo " <br><br> \n";
	}

	echo "<table><tr><td>\n";

	if ($disc)
	{
		if ($grpsk)
			$oc = 'on_disc_click(event)';
		else
			$oc = false;
		echo disc_draw($disc['LR'], $disc['UD'], $oc);
	}
	
	echo "</td><td> \n";
	
	echo "<table><tr><td>";

	if ($grpsk)
		$mina = $nn . 's';
	else
		$mina = "mina";

	echo "<td class='twm' >\n";
	echo "Detta är $mina styrkor:\n<ul>\n";

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
	echo "Detta är $mina svagheter:\n<ul>\n";

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
	echo "Detta är $mina motivatorer:\n<ul>\n";

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

	echo "\t<br>\n";

	if (!$grpsk) {

		echo "\t <img width=100% src='allafargtext.png' /> <br /> <br /> <br /> \n";
		echo "\t Skriv ut ovanstående discanalys, stryk under de motivatorer som är de viktigaste för dig, och diskutera i gruppen om andra delar din uppfattning om din beteendeprofil. Sätt sedan in i din handlingsplan. Ta med detta dokument till examinationen på Nivå 1 i nästa steg. <br /> <br /> \n";
		echo "\t <iframe width='85%' height='850px' src=' ../common/Disc2014.pdf' /> </iframe> <br /> <br /> <br /> \n";
		echo "\t Skriv också ut och läs igenom ovanstående detaljerade tolkning av olika beteendestilar och behåll i din utbildningspärm. Du kommer att lära dig mer om olika beteendestilar längre fram i utbildningen.<br> (Skriv ut dokunentet genom att först ställa markören på dokumentet och högerklicka. Välj: Öppna med förhandsvisning. Sedan skriv ut.) Sätt in dokumentet i din utbildningspärm. <br /> \n";

	} else {

		echo "\n\n<div id='disc_replace'> </div>\n";

	}

}

index();

?>

</body>
</html>
