
<?php

$RETURNTO = 'collect';

include_once 'debug.php';
include_once 'head.php';
include_once 'common_php.php';
include_once 'tagOut.php';
include_once 'connect.php';
include_once 'main.js.php';
include_once 'util.php';


echo "</head>\n";

function index()
{
	$to = new tagOut;

	$to->startTag("body");

	$to->startTag('div', 'id="main" class="main"');
	
	$to->startTag('div', 'id="lbl"');
	$to->stopTag('div');
	$to->scTag('br');
	
	$to->startTag('div');
	$to->scTag('br');
	$to->scTag('img', 'width=50% src="logo.png"');

	$to->stopTag('div');


	// 1 - egenskattning

	//$to->regLine('<hr>');
	//$to->regLine('<h1> Egenskattning </h1>');

	$pid = getparam("pid", false);

	if (!$pid) {
		$to->closeAll();
		return false;
	}

	$names = [];
	$egens = [];

	$vg = ROD('data', ['pers', 'type'], [$pid, 201], 'value_a', false);
	$names[201] = "V&auml;rdegrund";
	$egens[201] = $vg;
	$to->regLine("V&auml;rdegrund : $vg <br> \n");

	$ms = ROD('data', ['pers', 'type'], [$pid, 202], 'value_a', false);
	$names[202] = "MissionStatement";
	$egens[202] = $ms;
	$to->regLine("MissionStatement : $ms <br> \n");

	$mot = ROD('data', ['pers', 'type'], [$pid, 302], 'value_a', false);
	$names[302] = "Motivation";
	$egens[302] = $mot;
	$to->regLine("Motivation : $mot <br> \n");

	$sam = ROD('data', ['pers', 'type'], [$pid, 105], 'value_b', false);
	$names[105] = "Samarbete";
	$egens[105] = $sam;
	$to->regLine("Samarbete : $sam <br> \n");

	$str = ROD('data', ['pers', 'type'], [$pid, 101], 'value_b', false);
	$names[101] = "Styrkor";
	$egens[101] = $str;
	$to->regLine("Styrkor : $str <br> \n");

	$kom = ROD('data', ['pers', 'type'], [$pid, 103], 'value_b', false);
	$names[103] = "Kommunikation";
	$egens[103] = $kom;
	$to->regLine("Kommunikation : $kom <br> \n");

	$mal = ROD('data', ['pers', 'type'], [$pid, 104], 'value_a', false);
	$names[104] = "M&aring;ls&auml;ttning";
	$egens[104] = $mal;
	$to->regLine("M&aring;ls&auml;ttning : $mal <br> \n");


	// 2 - gruppskattning

	$to->regLine('<hr>');
	$to->regLine('<h1> Gruppskattning </h1>');


	global $emperator;

	$grp = "";
	$pnam = "";

	$query = "SELECT * FROM pers WHERE pers_id=" . $pid;
	$res = mysqli_query( $emperator, $query );
	if ($res) if ($row = mysqli_fetch_array($res))
	{
		$grp = $row['grupp'];
		$pnam = $row['name'];
	}

	$pidlst = [];
	$namlst = [];

	$query = "SELECT * FROM pers";
	$res = mysqli_query( $emperator, $query );
	if ($res) while ($row = mysqli_fetch_array($res))
	{
		if (!arr_overlap($grp, $row['grupp'])) continue;

		$pp = $row['pers_id'];

		if ($pp == $pid) continue;

		$pidlst[] = $pp;
		$namlst[$pp] = $row['name'];
	}

	//$gsl = [321, 322, ];

	foreach ($pidlst as $pp)
	{
		$for = $pid;
		$by = $pp;
		$nn = $namlst[$pp];

		// $to->regLine("Gruppskattning utförd av : $nn ($pp) <br> \n");

        /*
		$cnt = 0;

		for ($g = 

		$x = get_gr_val($by, $for, 321);

		if ($x) ++$cnt;

		$rr = $rr && get_gr_val($by, $for, 202);

		if ($rr) $val_cnt += 1;

		*/
	}





	


//  ett gap analys

echo "<script>\n\n";
echo "targets = [ 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99, 99 ]; \n\n";


echo "targ_s  = [ 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85  ]; \n\n";

echo "val_e =   [ 78, 25, 34, 98, 56, 33, 90, 34, 56, 67, 23, 99, 78, 56, 65, 23, 99, 78, 56 ];  \n\n";

echo "val_b =   [ 55, 56, 23, 67, 76, 34, 78, 34, 99, 12, 34, 34, 78, 34, 99, 12, 34, 88, 34 ];  \n\n";

echo "short_desc = [ 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's' ];  \n\n";

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

echo "<script> DrawSpider('SpiderCanvas', 7, targets, targ_s, val_e, val_b, short_desc, '$pnam' ); </script> \n";

echo "<br><br>\n";


	$to->stopTag('div');
	$to->stopTag('body');



}


index();

?>

</html>
