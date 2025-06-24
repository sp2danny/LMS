
<!-- inlude mentor.php -->

<?php

$RETURNTO = 'mentor';

include_once 'debug.php';

include_once 'head.php';
include_once 'common.php';
include_once 'tagOut.php';
include_once 'connect.php';
include_once 'util.php';
include_once 'getem.php';

function ptbl($to, $prow, $mynt, $score=0)
{
	$heartfile = fopen("heart.txt", "r");
	$txt = "";
	if ($heartfile) {
		$arr = [];
		while (true) {
			$buffer = fgets($heartfile, 4096);
			if (!$buffer) break;
			$buffer = trim($buffer);
			$len = strlen($buffer);
			if ($len == 0) continue;
			$arr[] = $buffer;
		}
		$top = count($arr)-1;
		if ($top > 0)
			$txt = $arr[rand(0,$top)];
	}

	$div = "<div> <img src='heart.png' style='vertical-align: middle;' width='100px' /> <span style='vertical-align: middle;'> $txt </span> ";

	$wtelf = '""';
	$to->startTag('table', "class=$wtelf");
	$to->regLine("<tr> <td class=$wtelf > Kundnummer    </td> <td class=$wtelf > " . $prow[ 'pers_id' ] . "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td> <td class=$wtelf > Guldmynt     </td> <td class=$wtelf > $mynt   </td> </tr>");
	$to->regLine("<tr> <td class=$wtelf > Namn          </td> <td class=$wtelf > " . $prow[ 'name'    ] . "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td> <td class=$wtelf > Po&auml;ng   </td> <td class=$wtelf > $score  </td> </tr>");
	$to->regLine("<tr> <td class=$wtelf >               </td> <td class=$wtelf > " . ""                 . "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td> <td colspan=2 rowspan=2 class=$wtelf > $div </td>  </tr>");
	$to->regLine("<tr> <td class=$wtelf > Medlem sedan  </td> <td class=$wtelf > " . $prow[ 'date'    ] . "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td>  </tr>");
	$to->stopTag('table');
}


function index($local, $common)
{
	global $RETURNTO;

	debug_log("index() in $RETURNTO.php");

	global $emperator;

	$to = new tagOut;

	$data = new Data;

	$data->pnr = getparam("pnr", "0");

	$prow = false;
	$mynt = 0;

	$pid = getparam("pid", "0");

	if ($data->pnr != 0) {
		$query = "SELECT * FROM pers WHERE pnr='" . $data->pnr . "'";
		$res = mysqli_query($emperator, $query);
		if ($res)
			$prow = mysqli_fetch_array($res);
	}

	if (!$prow) {
		$query = "SELECT * FROM pers WHERE pers_id='" . $pid . "'";
		$res = mysqli_query($emperator, $query);
		$prow = mysqli_fetch_array($res);
	}

	$name = $prow['name'];
	$pid = $prow['pers_id'];
	$data->pnr = $prow['pnr'];
	$data->pid = $pid;
	$data->tag = explode(",", $prow['tag']);

	$grp = $prow['grupp'];
	$data->grp = $grp;

	$grpsk = getparam("grpsk", "egen");
	if ($grpsk == "egen") $grpsk = false;
	$data->grpsk = $grpsk;
	debug_log("grpsk: " . $grpsk);

	$query = 'SELECT * FROM data WHERE pers=' . $pid . ' AND type=4';
	$res = mysqli_query($emperator, $query);
	if ($row = mysqli_fetch_array($res)) {
		$mynt = $row['value_a'];
	}

	$eol = "\n";
	
	$dagens = array();
	$ord = fopen("../common/ord.txt", "r");
	if ($ord)
	{
		while (true) {
			$buffer = fgets($ord, 4096);
			if (!$buffer) break;
			$buffer = trim($buffer);
			$len = strlen($buffer);
			if ($len == 0) continue;
			$cc = 0;
			for ($idx=0; $idx<$len; ++$idx)
				$cc = $cc ^ ord($buffer[$idx]);
			if ($len != 105 || $cc != 8)
				$dagens[] = $buffer;
		}
	}
	$data->dagens = $dagens;

	$title = 'Min Mentor Sida';

	$data->name = $name;
	$data->mynt = $mynt;
	
	
	echo <<<EOT

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body {
	background-color: #ffffff;
	/*margin-top: 50px;
	margin-right: 450px;
	margin-left: 200px;
	margin-bottom: 75px;*/
}

.empg {
	background-color:#96BF0D;
	font-size:15px;
}

button.ilbbaicl {
  font-size: 24px;
  font-weight: bold;
  width: 100%;
}

h5.regular {
  font-size: 16px;
  font-weight: normal;
}

body.nomarg {
    background-color: #ffffff;
    margin-top: 5px;
    margin-right: 5px;
    margin-left: 5px;
    margin-bottom: 5px;
}

div.hdr {
  font-size: 18px;
  font-weight: bold;
}

table tr td {
  padding-left:   20px;
  padding-right:  20px;
  padding-top:    1px;
  padding-bottom: 1px;
}
table.visitab {
  border: 2px solid black;
  margin-top: 2px;
  border-collapse: collapse;
}
td.visitab {
  border: 1px solid grey;
  border-collapse: collapse;
}

p.allc
{
	text-align: center;
	justify-content: center;
	text-align-vertical
	vertical-align: middle;
}

.content {
  padding: 3px 8px;
  display: none;
  overflow: hidden;
  background-color: white;
}

br.hs {
	line-height: 9px;
}

.fse {
	border: none;
	width: 100%;
	height: 5000px;
}


.tooltip {
  position: relative;
  display: inline-block;
}

.tooltiptext {
  border: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  /*width: 350px;*/
  background-color: #eee;
  color: #000;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  
  /* Position the tooltip */
  position: absolute;
  z-index: 1;
  top: 100%;
  left: 50%;
  margin-left: -140px;
  margin-top:  5px;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}

</style>

EOT;


	$to->startTag('script');
	
	$to->regLine('function doChangeB() { ');
	$to->regLine("  window.location.href = '" . getMin($data) . "'; ");
	$to->regLine('}');

	$to->regLine('function doChangeD() { ');
	$to->regLine("  window.location.href = '" . getUtb($data) . "'; ");
	$to->regLine('}');

	$scrn = $_SERVER["SCRIPT_NAME"];
	$curPageName = substr($scrn, strrpos($scrn,"/")+1);  


	$to->stopTag('script');

	echo '<title>' . $title . '</title>' . $eol;
	echo '</head>' . $eol;

	$to->startTag('body');



	$to->startTag ('div', 'class="sidenav"');
	$to->startTag ('div', 'class="indent"');

	$to->startTag ('div');
		
	$to->regLine("<button id='BtnCP'  onClick='doChangeB()'> Min Sida </button>");

	$eg = empgreen();
		
	$grp = getGrp($data);

	$to->regLine("<br class='hs'>");
	$to->regLine("<button id='BtnUtb' class='empg' onClick='doChangeD()'> &nbsp;Min Utbildning&nbsp; </button>");

	$to->regline  ('<hr>');
	$to->stopTag  ('div');

	echo <<<EOT
				<h3> Coach interface </h3>
				<hr />
				<h5> Fr&aring;gor </h5>
				<a href='Coachfragor/avsnitt-1.1.docx'> Avs 1.1 </a> <br />
				<a href='Coachfragor/avsnitt-1.2.docx'> Avs 1.2 </a> <br />
				<a href='Coachfragor/avsnitt-1.3.docx'> Avs 1.3 </a> <br />
				<a href='Coachfragor/avsnitt-1.4.docx'> Avs 1.4 </a> <br />
				<a href='Coachfragor/avsnitt-1.5.docx'> Avs 1.5 </a>

				<hr style="height:2px; visibility:hidden;" />

				<a href='Coachfragor/avsnitt-2.1.docx'> Avs 2.1 </a> <br />
				<a href='Coachfragor/avsnitt-2.2.docx'> Avs 2.2 </a> <br />
				<a href='Coachfragor/avsnitt-2.3.docx'> Avs 2.3 </a> <br />
				<a href='Coachfragor/avsnitt-2.4.docx'> Avs 2.4 </a> <br />

				<hr style="height:2px; visibility:hidden;" />

				<a href='Coachfragor/avsnitt-3.1.docx'> Avs 3.1 </a> <br />
				<a href='Coachfragor/avsnitt-3.2.docx'> Avs 3.2 </a> <br />

				<hr />
				<h5> Process </h5>
				<a href='Coachprocessen/Coachprocessen-2023.docx'> 2023 </a> <br />
				<hr />
				<h5> PUP </h5>
				<a href='MinHandlingsplan/handlingsplan.docx'> 2023 </a> <br />
				<hr />

				<hr style="height:2px; visibility:hidden;" />

EOT;

	$to->stopTag('div'); // indent
	$to->stopTag('div'); // sidenav

	$to->startTag('div', 'id="main" class="main"');
	
	$to->startTag('div', 'id="lbl"');
	$to->stopTag('div');
	$to->scTag('br');
	

	$to->startTag('div');
	$to->scTag('br');
	$to->scTag('img', 'width=50% src="logo.png"');

	$grp = $data->grp;

	$at = getparam("at", '0');

	$to->stopTag('div');
	
	$to->regLine(' <div style="clear: both;"></div> ');
	$to->stopTag('div');
	$to->scTag('br');
	$to->scTag('br');

	$n = count($dagens);
	if ($n > 0) {
		$i = rand(0, $n-1);
		$to->regLine('<center>' . $dagens[$i] . '</center>');;
	}

	$tit = array();

	$n = count($tit);

	$to->scTag("hr");

	$to->startTag("table");
	$to->startTag("tr");

	$btn_txt = [];
	$btn_lnk = [];

	$ggg = explode(',', $data->grp);
	$tl = $data->tag;

	foreach ($ggg as $gg)
	{
		if (for_discard($gg)) continue;
		//if ($gg=='Admin') continue;
		$btn_txt[] = "Mina Kunder - " . $gg;
		$btn_lnk[] = "grplst.php?grp=" . $gg;
	}

	if (is_in($tl, 'admin'))
	{
		$btn_txt[] = "Grupp Hantering";
		$btn_lnk[] = "https://www.mind2excellence.se/coach/pgl2.php";

		$btn_txt[] = "Ta Bort Person";
		$btn_lnk[] = "cleanup_db.php";

	}

	$nb2 = "&nbsp;&nbsp;";

	$n = count($btn_txt);

	for ($i=0; $i<$n; ++$i) {
		
		if ($n>=5) {
			$half = (int) (($n+1) / 2);
			if ($i == $half) {
				$to->stopTag("tr");
				$to->startTag("tr");
			}
		}

		$to->startTag("td");

		$base  = "<a href='" . $btn_lnk[$i] . "'>";
		$base .= "<button class='ilbbaicl' ";
		$base .= "style=' border-radius: 9px; ";

		$fbn = $nb2 .  $btn_txt[$i] . $nb2;
		//if ($at == $i) {
		//	$base .= "border-style:inset;'";
		//	$to->regLine($base . " > " . $fbn . " </button>");
		//} else {
			$base .= "'";
			$to->regLine($base . "  > " . $fbn . " </button> </a>");
		//}

		$to->stopTag("td");
	}

	$to->stopTag("tr");

	$to->stopTag("table");


	$to->scTag("hr");

	ptbl($to, $prow, $mynt);

	$to->scTag("hr");

	$to->stopTag('body');
}

$local = "./";
$common = "./";

index($local, $common);

?> 

</html>

