
<!-- inlude utbildning.php -->

<?php

$RETURNTO = 'utbildning';

include_once 'process_cmd.php';
include_once 'cmdparse.php';
include_once 'progress.php';
include_once 'debug.php';

include_once 'head.php';
include_once 'common.php';
include_once 'tagOut.php';
include_once 'connect.php';
include_once 'roundup.php';
include_once 'util.php';

function ptbl($to, $prow, $mynt, $score=0)
{
	$arr = [];
	$arr[] = "Du &auml;r fin";
	$arr[] = "Jag &auml;r bra";
	$arr[] = "Jag gillar mig sj&auml;lv som jag &auml;r";
	$arr[] = "Jag vill utvecklas varje dag";
	$arr[] = "Jag tror p&aring; mig sj&auml;lv";
	$arr[] = "Jag &auml;r tacksam f&ouml;r varje dag";

	$top = count($arr)-1;
	$txt = $arr[rand(0,$top)];

	$div = "<div> <img src='heart.png' style='vertical-align: middle;' width='100px' /> <span style='vertical-align: middle;'> $txt </span> ";

	$wtelf = '""';
	$to->startTag('table', "class=$wtelf");
	$to->regLine("<tr> <td class=$wtelf > Kundnummer    </td> <td class=$wtelf > " . $prow[ 'pers_id' ] . "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td> <td class=$wtelf > Guldmynt     </td> <td class=$wtelf > $mynt   </td> </tr>");
	$to->regLine("<tr> <td class=$wtelf > Namn          </td> <td class=$wtelf > " . $prow[ 'name'    ] . "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td> <td class=$wtelf > Po&auml;ng   </td> <td class=$wtelf > $score  </td> </tr>");
	$to->regLine("<tr> <td class=$wtelf >               </td> <td class=$wtelf > " .                      "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td> <td colspan=2 rowspan=2 class=$wtelf > $div </td>  </tr>");
	$to->regLine("<tr> <td class=$wtelf > Medlem sedan  </td> <td class=$wtelf > " . $prow[ 'date'    ] . "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td>  </tr>");
	$to->stopTag('table');
}

function to_link($alldata, $str)
{
	$p = strpos($str, '.');
	if ($p===false) return "";
	$bat = substr($str, 0, $p);
	$seg = substr($str, $p+1);
	
	foreach ($alldata as $block) {
		if ($block->battNum != $bat) continue;
		foreach ($block->lines as $line) {
			if ($line->segment != $seg) continue;
			return $line->link;
		}
	}
	return "";
}

function getCP($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/minsida.php';
	if ($data->pid != 0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr != 0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

function getUtb($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/personal.php';
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

function getKurs($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/kurser.php';
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

function getSett($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/cp_settings.php';
	//$cp_have = false;
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return ' <iframe src="' . $cp_site . '" style="min-height:100vh;width:100%" frameborder="0" > ';
}


function survOut($to, $tn, $filt)
{
	global $emperator;

	$pnr = getparam('pnr');
	$pid = getparam('pid');
	if ($pnr && ! $pid) {
		$query = "SELECT * FROM pers WHERE pnr='$pnr'";
		$res = mysqli_query($emperator, $query);
		if ($res) if ($row = mysqli_fetch_array($res))
			$pid = $row['pers_id'];
	}

	$n = 0;
	$query = "SELECT * FROM surv WHERE type='$tn' AND pers='$pid';";
	$res = mysqli_query( $emperator, $query );
	if ($res) while ($row = mysqli_fetch_array($res)) {
		$seq = $row['seq'];
		$sid = $row['surv_id'];
		++$n;
	}
	
	if ($n<=0) {
		$to->regLine(' --- inga surveys ännu ---');
	} else if ($n==1) {
		$lnk = "onesurv.php?sid=$sid&seq=$seq&pid=$pid&st=$tn&filt=$filt";
		debug_log('embed link : ' . $lnk);
		$to->scTag('embed', "type='text/html' src='$lnk' width='1200' height='1600' ");
	} else {
		$lnk = "allsurv.php?pid=$pid&st=$tn&filt=$filt";
		debug_log('embed link : ' . $lnk);
		$to->scTag('embed', "type='text/html' src='$lnk' width='1200' height='1600' ");
	}
}

function pagename($ns, $cpn) {
	$href = $cpn;
	if ($ns)
		$href = addKV($href, 'noside', 'true');
	$href = addKV($href, 'pnr', getparam('pnr'));
	return $href;
}

function index($local, $common)
{
	global $RETURNTO;

	debug_log("index() in $RETURNTO.php");

	global $emperator;

	$to = new tagOut;
	
	$data = new Data;

	$name = getparam("name");

	$data->pnr = getparam("pnr", "0");

	$query = "SELECT * FROM pers WHERE pnr='" . $data->pnr . "'";

	$pid = getparam("pid", "0");

	$res = mysqli_query($emperator, $query);
	$mynt = 0;
	if (!$res)
	{
		$to->regLine('DB Error');
	} else {
		$prow = mysqli_fetch_array($res);
		$name = $prow['name'];
		if (!$prow) {
			$to->regLine('DB Error');
		} else {
			$pid = $prow['pers_id'];
			$query = 'SELECT * FROM data WHERE pers=' . $pid . ' AND type=4';
			$res = mysqli_query($emperator, $query);
			if ($row = mysqli_fetch_array($res)) {
				$mynt = $row['value_a'];
			}
		}
	}
	
	$data->pid = $pid;
	
	for ($qi=11; $qi<20; ++$qi) {
		$query = "SELECT value_c FROM data WHERE pers=" . $pid . " AND type=" . $qi;
		$res = mysqli_query($emperator, $query);
		if ($res) {
			if ($row = mysqli_fetch_array($res)) {
				$srp = new SRP;
				$srp->str = "%get-" . $qi . "%";
				$srp->repl = $row['value_c'];
				$data->replst[] = $srp;
				echo '<!-- ' . 'storing ' . $srp->str . ' as ' . $srp->repl . ' -->';
			}
		}
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

	$title = 'Utbildningen';

	$data->name = $name;
	$data->mynt = $mynt;

	$noside = (getparam("noside", "") == "true");

	echo <<<EOT


<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
EOT;

	if (!$noside)
	{
		echo <<<EOT

		/*p.main {
		  padding-left:   40px;
		}
		body {
			background-color: #ffffff;
			margin-top: 30px;
			margin-right: 440px;
			margin-left: 30px;
			margin-bottom: 55px;
		}*/
EOT;
	} else {
		echo <<<EOT
		p.main {
		  padding-left:   0px;
		}
		body {
			background-color: #ffffff;
			margin-top: 0px;
			margin-right: 0px;
			margin-left: 0px;
			margin-bottom: 0px;
		}
EOT;
		
	}

	echo <<<EOT


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
  padding-left:   5px;
  padding-right:  5px;
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

.progbar {
  border: 1px solid grey;
  border-radius: 5px;
  color : #000;
  background-color : #f1f1f1;
}

.auto-container {
  content : "";
  display : table;
  clear : both;
}


p.allc
{
  text-align: center;
  justify-content: center;
  text-align-vertical;
  vertical-align: middle;
}

table.wtelf {
  border: 2px solid #000;
  margin-top: 25px;
  border-collapse: collapse;
}
td.wtelf {
  border: 1px solid #222;
  margin-top: 7px;
  margin-bottom: 7px;
  border-collapse: collapse;
}

.collapsible {
  background-color: #FFF;
  color: black;
  cursor: pointer;
  padding: 8px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.collapsible:hover {
  background-color: #EEE;
}
.content {
  padding: 3px 3px;
  display: none;
  overflow: hidden;
  background-color: white;
}

br.hs {
  line-height: 9px;
}

ul, li {
  font-size: 15px;
  padding: 3px;
  margin-left: 22px;
}

</style>

EOT;

	$to->startTag('script');

	$to->regLine('function doChangeB() { ');
	$to->regLine("  window.location.href = '" . getCP($data) . "'; ");
	$to->regLine('}');

	$to->regLine('function doChangeC() { ');
	$to->regLine('  var obj = document.getElementById("alt"); ');
	$to->regLine('  var main = document.getElementById("main"); ');
	$to->regLine("  site = '" . getSett($data) . "'; ");
	$to->regLine('  if (obj.getAttribute("state") == "2") { ');
	$to->regLine('    main.style.display = "block"; ');	
	$to->regLine('    obj.innerHTML = ""; ');
	$to->regLine("    document.getElementById('BtnCP').style.borderStyle = 'outset'; ");
	$to->regLine("    document.getElementById('BtnSett').style.borderStyle = 'outset'; ");
	$to->regLine('    obj.setAttribute("state", "0"); ');
	$to->regLine('  } else { ');
	$to->regLine('    main.style.display = "none"; ');
	$to->regLine('    obj.innerHTML = site; ');
	$to->regLine("    document.getElementById('BtnCP').style.borderStyle = 'outset'; ");
	$to->regLine("    document.getElementById('BtnSett').style.borderStyle = 'inset'; ");
	$to->regLine('    obj.setAttribute("state", "2"); ');
	$to->regLine('  }');
	$to->regLine('}');

	$to->regLine('function doChangeD() { ');
	$to->regLine("  window.location.href = '" . getUtb($data) . "'; ");
	$to->regLine('}');

	$to->regLine('function doChangeE() { ');
	$to->regLine("  window.location.href = '" . getKurs($data) . "'; ");
	$to->regLine('}');

	$to->regLine('function setProgress(pro, cnv) {');
	$to->regLine('  var ctx = cnv.getContext("2d");');
	$to->regLine('  ctx.fillStyle = "#F2F3F7";');
	$to->regLine('  ctx.fillRect(0,0,200,200);');
	$to->regLine('  ctx.strokeStyle = "#000";');
	$to->regLine('  ctx.lineWidth = 12;');
	$to->regLine('  ctx.beginPath();');
	$to->regLine('  ctx.arc(100, 100, 75, 1 * Math.PI, 2 * Math.PI);');
	$to->regLine('  ctx.stroke(); ');
	$to->regLine('  ctx.strokeStyle = "#fff";');
	$to->regLine('  ctx.lineWidth = 10;');
	$to->regLine('  ctx.beginPath();');
	$to->regLine('  ctx.arc(100, 100, 75, 1.01 * Math.PI, 1.99 * Math.PI);');
	$to->regLine('  ctx.stroke();');

	$to->regLine('  if (pro > 0) {');
	$to->regLine('    ctx.strokeStyle = "#7fff7f";');
	$to->regLine('    ctx.lineWidth = 10;');
	$to->regLine('    ctx.beginPath();');
	$to->regLine('    ctx.arc(100, 100, 75, 1.01 * Math.PI, (1.01+0.98*(pro/100.0)) * Math.PI);');
	$to->regLine('    ctx.stroke();');
	$to->regLine('  }');

	$to->regLine('  ctx.fillStyle = "#7f7";');
	$to->regLine('  ctx.lineWidth = 1;');
	$to->regLine('  ctx.strokeStyle = "#000";');
	$to->regLine('  ctx.font = "35px Arial";');
	$to->regLine('  ctx.textAlign = "center"; ');
	$to->regLine('  ctx.fillText( pro.toString() + " %", 100, 98); ');
	$to->regLine('  ctx.strokeText( pro.toString() + " %", 100, 98); ');
	$to->regLine('}');
	
	$scrn = $_SERVER["SCRIPT_NAME"];
	$curPageName = substr($scrn, strrpos($scrn,"/")+1);  

	$to->regLine("function newpage(i) { ");
	$href = $curPageName;
	if ($noside)
		$href = addKV($href, 'noside', 'true');
	$href = addKV($href, 'pnr', getparam('pnr'));
	$to->regLine("	window.location.href = '" . pagename($noside, $curPageName) . "&at=' + i.toString();");
	$to->regLine("}");

	$to->stopTag('script');

	echo '<title>' . $curPageName . " - " . $title . '</title>' . $eol;
	echo '</head>' . $eol;

	$to->startTag('body');

	$side = fopen("styrkant.txt", "r") or die("Unable to open file!");

	if (!$noside)
	{

		$to->startTag ('div', 'class="sidenav"');
		$to->startTag ('div', 'class="indent"');

		$to->startTag ('div');
		
		$to->regLine("<button id='BtnSett' onClick='doChangeC()'> Settings </button>");
		
		if (getparam("sticp", "0") == "1") {
			$to->regLine("<button id='BtnCP'  onClick='doChangeB()'> Min Sida </button>");
		} else {
			$to->regLine("<button id='BtnCP' onClick='doChangeB()'> Min Sida </button>");
		}

		$eg = empgreen();

		//$to->regLine("<br class='hs'> <button id='BtnUtb' style='background-color:" . $eg . ";font-size:15px;' onClick='doChangeD()'> &nbsp;Min Utbildning&nbsp; </button>");
		$to->regLine("<br class='hs'> <button id='BtnKrs' style='background-color:" . $eg . "; font-size:15px;' onClick='doChangeE()'> &nbsp;Våra Event och Kurser&nbsp; </button>");

		$to->regline  ('<hr>');
		$to->stopTag  ('div');

		while (true) {
			$buffer = fgets($side, 4096); // or break;
			if (!$buffer) break;
			$cmd = cmdparse($buffer);
			if ($cmd->is_command) {
				switch ($cmd->command) {
					case 'text':
						$txt = $cmd->rest;
						$txt = str_replace('%name%', $data->name, $txt);
						$txt = str_replace('%coin%', $data->mynt, $txt);
						$txt = str_replace('%seg%',  $data->snum, $txt);
						$txt = str_replace('%bat%',  $data->bnum, $txt);
						$to->regLine($txt);
						break;
					case 'link':
						$lnk = $cmd->params[0];
						$to->startTag("a", "href=$lnk");
						$to->regLine($cmd->params[1]);
						$to->stopTag("a");
						break;
					case 'line':
						$to->regLine('<hr color="' . $cmd->rest . '" />');
						break;
					case 'image':
						if (count($cmd->params)>1) {
							$to->regLine('<img width=' . $cmd->params[0] . '%  src="' . $cmd->params[1] . '" /> <br />');
						} else {
							$to->regLine('<img src="' . $cmd->params[0] . '" /> <br />');
						}
						break;
					case 'name':
						$to->regLine($prow['name']);
						break;
					case 'coin':
						$to->regLine($mynt . ' mynt.');
						break;
					case 'seg':
						$to->regLine($data->snum);
						break;
					case 'time':
						$to->startTag('div', 'class="indent" id="TimerDisplay"');
						$to->stopTag('div');
						break;
					case 'break':
						$n = (int)$cmd->rest;
						for ($i=0; $i<$n; ++$i)
							$to->regLine('<br />');
						break;
					case 'sound':
						// sound
						break;
					case 'prog':
						$pro = 0; // (int)progress($data->snum, $maxseg);
						if ($pro<0) $pro = 0;
						if ($pro>100) $pro = 100;
						$to->regLine('<canvas id="myCanvas" width="200" height="120" ></canvas>');
						$to->startTag('script');
						$to->regLine('var pro = ' . $pro . ';');
						$to->regLine('var canvas = document.getElementById("myCanvas");');
						$to->regLine('//setProgress(pro, canvas);');
						$to->stopTag('script');
						break;
				}
			}
		}

		$to->stopTag('div');
		$to->stopTag('div');

	}

	fclose($side);

	$to->startTag('div', 'id="main" class="main"');

	$to->regLine('<br /> <img width=50%  src="logo.png" /> <br /> <br /> <br />');

	$n = count($dagens);
	if ($n > 0) {
		$i = rand(0, $n-1);
		$to->regLine('<center>' . $dagens[$i] . '</center>');;
	}

	$tit = array();

	$n = count($tit);

	$at = getparam("at", '0');

	$to->scTag("hr");

	$to->startTag('table');
	$to->startTag('tr');
	$to->startTag('td');

	ptbl($to, $prow, $mynt);
	
	$to->stopTag('td');
	$to->startTag('td');
	
	$to->regLine('&nbsp;&nbsp;&nbsp;');
	
	$to->stopTag('td');
	$to->startTag('td');
	
	$to->scTag("img", "src='niva_sc.png'");

	$to->stopTag('td');
	$to->stopTag('tr');
	$to->stopTag('table');

	/*
	$to->scTag("hr");

	$to->startTag("a", "href='next.php'");

	$to->startTag("div", "style='display:flex; justify-content: center'");

	$to->regLine(" <table> <tr> <td align='center' > "); 
	$to->regLine(" N&auml;sta steg ");
	$to->regLine(" </td> </tr> <tr> <td> "); 

	$to->scTag("img", "src='next.png'");

	$to->regLine(" </td> </tr> </table> "); 

	$to->stopTag('div');
	$to->stopTag('a');
	*/

	$to->scTag("hr");

	$tit = array();

	$at = getparam("at", '0');

	$to->startTag("table");
	$to->startTag("tr");

	$utb_file = fopen("utb.txt", "r");
	$utb_ini = readini($utb_file);
	fclose($utb_file);

	$n = $utb_ini['general']['levels.count'];

	$ww = $utb_ini['general']['button.width'];
	$hh = $utb_ini['general']['button.height'];

	for ($i=1; $i<=$n; ++$i)
	{
		$tit[] = 'Niv&aring; ' . $i;
	}

	$nb2 = "&nbsp;&nbsp;";
	$i = 0;

	$start = -1;
	$stop = -1;

	$alldata = roundup($data->pnr, $data->pid, $data->name, true);
	$first = true;

	foreach ($tit as $value) {
		++$i;
		$to->startTag("td");

		$base = "<div class='ilbbaicl' ";
		$base .= "style=' border-radius: 9px; ";

		$seg = 'level.' . $i;
		$img = $utb_ini[$seg]['img'];

		$b1 = $utb_ini[$seg]['batt.start'];
		$b2 = $utb_ini[$seg]['batt.stop'];

		$b_tot = 0;
		$b_don = 0;

		foreach ($alldata as $block) {
			$nn = $block->battNum;
			if ($nn < $b1) continue;
			if ($nn > $b2) continue;
			++$b_tot;
			if ($block->allDone)
				++$b_don;
		}

		$to->startTag("center");
		if ($b_tot == $b_don) {
			$to->regLine("<img src='corr.png' /> <br /> ");
		} else if ($first) {
			$to->regLine("<img src='here.png' style='transform: rotate(90deg);' /> <br /> ");
			$first = false;
		} else {
			$to->regLine("<img src='blank.png' /> <br /> ");
		}
		$to->stopTag("center");

		$pn = pagename($noside, "personal.php");
		$pn = addKV($pn, "at", $i);
		$to->startTag("a", "href='$pn'");
		$to->startTag('div', 'class="auto-container"');

		$base .= "background: url($img); background-size: cover; width:" . round(0.9*$ww) . "px; height:" . round(0.9*$hh) . "px; ";

		if ($at == $i) {
			$base .= "border-style:inset;'";
			$to->regLine($base . " > " . /* $nb2 .  $value . $nb2 . */ " </div>");
			$start = $utb_ini[$seg]['batt.start'];
			$stop = $utb_ini[$seg]['batt.stop'];
		} else {
			$base .= "'";
			//$to->regLine($base . " onclick='newpage(".$i.")' > " . /* $nb2 . $value . $nb2 . */ " </button>");
			$to->regLine($base . " > </div>");
		}
		$to->scTag("br");

		$to->regLine("<h3> " . $utb_ini[$seg]['name.major'] . " </h3>");
		$to->regLine("<h5 class='regular'> " . $utb_ini[$seg]['name.minor'] . " </h5> <br>");

		//$to->regLine("<small> " . $b_don . " / " . $b_tot . " </small> <br> " );

		if ($b_tot >= 1) {
			$pro = intval(($b_don * 100) / $b_tot);
			if ($pro<=0) $pro = 1;
			$col = $utb_ini[$seg]['color'];
			$bkg = $utb_ini[$seg]['background'];

			//$to->regLine(" <progress value='$pro' max='100' width='" . $ww . "px'  style='bar-color: $col' > </progress> <br>  ");

			$to->regLine("<div class='progbar' style='background-color : $bkg;' >");
			$to->regLine("<div style='background-color : $col; border-radius: 5px; height:18px; width:$pro%;' > </div> ");
			$to->regLine("</div><br>");
		}

		$to->stopTag("div");

		$to->stopTag("a");

		$to->stopTag("td");
	}

	$to->stopTag("tr");

	$to->stopTag("table");

	$to->scTag("hr");

	$atnum = -1;

	foreach ($alldata as $block) {

		$nn = $block->battNum;
		if ($nn < $start) continue;
		if ($nn > $stop) continue;

		echo '<button type="button" class="collapsible"> ' /* . $block->battNum */ . ' &nbsp; ';
		echo '<img width="12px" height="12px" src="';
		if ($block->someDone) {
			echo 'here';
			$atnum = $block->atnum;
		}
		else if ($block->allDone)
			echo "corr";
		else
			echo "blank";
		echo '.png" > ';
		echo $block->name . ' </button>';
		echo '<div class="content" id="CntDiv' . $block->battNum .'" >';
		echo '<ul style="list-style-type:none">';
		$frst = true;
		foreach ($block->lines as $line) {
			echo '<li> <img width="12px" height="12px" src="';
			if ($line->hasDone) {
				echo "corr";
			} else if ($line->isLink) {
				if ($frst)
					echo 'here';
				else
					echo "blank";
				$frst = false;
			} else {
				echo "blank";
			}
			echo '.png" > ';
			if ($line->isLink)
				echo '<a href="' . $line->link . '" > ';
			echo $line->name;
			if ($line->isLink)
				echo ' </a> ';
			echo '</li>';
		}
		echo '</ul></div>';
		echo "\n";
	}
	//echo '</ul>';

	echo '<script> ';
	if ($atnum != -1)
		echo ' document.getElementById("CntDiv' . $atnum . '").style.display = "block";';

	if (getparam("at", '0') != 0)
	{
		$to->regLine('window.scrollTo(0, 850);');
	}

	echo <<<EOT

var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    //this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
<!-- Start of LiveAgent integration script: Chat button: Chattknapp -->
<script type="text/javascript">
(function(d, src, c) { var t=d.scripts[d.scripts.length - 1],s=d.createElement('script');s.id='la_x2s6df8d';s.defer=true;s.src=src;s.onload=s.onreadystatechange=function(){var rs=this.readyState;if(rs&&(rs!='complete')&&(rs!='loaded')){return;}c(this);};t.parentElement.insertBefore(s,t.nextSibling);})(document,
'https://emperator.liveagent.se/scripts/track.js',
function(e){ LiveAgent.createButton('d7903992', e); });
</script>
<!-- End of LiveAgent integration script -->

EOT;

	$to->stopTag('div');

	if (getparam("sticp", "0") == "1") {
		$to->regLine('<div id="alt" class="xxx" state="0" >');
		$to->regLine(getCP($data));
		$to->regLine('</div>');
	} else {
		$to->regLine('<div id="alt" class="xxx"></div>');
	}

	$to->stopTag('body');
}

$local = "./";
$common = "./";

index($local, $common);

?> 

</html>

