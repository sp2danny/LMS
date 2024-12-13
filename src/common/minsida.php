
<!-- inlude minsida.php -->

<?php

$RETURNTO = 'minsida';

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
include_once 'stapel_disp.php';

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
	$to->regLine("<tr> <td class=$wtelf > Personnummer  </td> <td class=$wtelf > " . $prow[ 'pnr'     ] . "</td> <td class=$wtelf > &nbsp;&nbsp;&nbsp; </td> <td colspan=2 rowspan=2 class=$wtelf > $div </td>  </tr>");
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
	$cp_site = 'https://www.mind2excellence.se/site/common/minsida.php?noside=true';
	if ($data->pid != 0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr != 0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	// <iframe src="some.pdf" style="min-height:100vh;width:100%" frameborder="0"></iframe>
	return ' <iframe src="' . $cp_site . '" style="min-height:100vh;width:100%" frameborder="0" > ';
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

function rwd($ini, $seg, $key, $def)
{
	if (!array_key_exists($seg, $ini))
		return $def;
	if (!array_key_exists($key, $ini[$seg]))
		return $def;
	return $ini[$seg][$key];
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

function min_max($arr)
{
	$have = false;
	$min = $max = 0;
	foreach ($arr as $val)
	{
		if (!$have) {
			$min = $max = $val;
			$have = true;
		} else {
			if ($val < $min) $min = $val;
			if ($val > $max) $max = $val;
		}
	}
	return [$min, $max];
}

function collect_sum_diff($survs, $ids)
{
	$tot = 0;
	foreach ($ids as $id)
	{
		$mm = min_max($survs[$id]);
		$tot += ($mm[1] - $mm[0]);
	}
	return $tot;
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

	$query = 'SELECT * FROM data WHERE pers=' . $pid . ' AND type=4';
	$res = mysqli_query($emperator, $query);
	if ($row = mysqli_fetch_array($res)) {
		$mynt = $row['value_a'];
	}

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

	$title = 'Min Sida';

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
		}*/
		body {
			background-color: #ffffff;
			/*margin-top: 50px;
			margin-right: 450px;
			margin-left: 200px;
			margin-bottom: 75px;*/
		}
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
  padding: 3px 8px;
  display: none;
  overflow: hidden;
  background-color: white;
}

br.hs {
	line-height: 9px;
}

</style>

EOT;


	$to->startTag('script');
	
	$to->regLine('function db_update(tp, pid, a = "", b = "") { ');
	$to->regLine('  var str = "db_upd.php?tp=" + tp + "&pid=" + pid;');
	$to->regLine('  if (a!="") str += "&a=" + a;');
	$to->regLine('  if (b!="") str += "&b=" + b;');
	$to->regLine('  fetch(str);');
	$to->regLine('}');
	

	$to->regLine('function doChangeB() { ');
	$to->regLine('  var obj = document.getElementById("alt"); ');
	$to->regLine('  var main = document.getElementById("main"); ');
	$to->regLine("  site = '" . getCP($data) . "'; ");
	$to->regLine('  if(obj.getAttribute("state") == "1") { ');
	$to->regLine('    main.style.display = "block"; ');
	$to->regLine('    obj.innerHTML = ""; ');
	$to->regLine("    document.getElementById('BtnCP').style.borderStyle = 'outset'; ");
	$to->regLine("    document.getElementById('BtnSett').style.borderStyle = 'outset'; ");
	$to->regLine('    obj.setAttribute("state", "0"); ');
	$to->regLine('  } else { ');
	$to->regLine('    main.style.display = "none"; ');
	$to->regLine('    obj.innerHTML = site; ');
	$to->regLine("    document.getElementById('BtnCP').style.borderStyle = 'inset'; ");
	$to->regLine("    document.getElementById('BtnSett').style.borderStyle = 'outset'; ");
	$to->regLine('    obj.setAttribute("state", "1"); ');
	$to->regLine('  }');
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

	
	$scrn = $_SERVER["SCRIPT_NAME"];
	$curPageName = substr($scrn, strrpos($scrn,"/")+1);  
	
	$to->regLine("function newpage(i) { ");
	$href = $curPageName;
	if ($noside)
		$href = addKV($href, 'noside', 'true');
	$href = addKV($href, 'pid', $pid);
	$to->regLine("	window.location.href = '$href&at=' + i.toString(); ");
	$to->regLine("}");

	$to->stopTag('script');

	echo '<title>' . $title . '</title>' . $eol;
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

		$to->regLine("<br class='hs'> <button id='BtnUtb' style='background-color:" . $eg . ";font-size:15px;' onClick='doChangeD()'> &nbsp;Min Utbildning&nbsp; </button>");
		$to->regLine("<br class='hs'> <button id='BtnKrs' style='background-color:" . $eg . ";font-size:15px;' onClick='doChangeE()'> &nbsp;Våra Event och Kurser&nbsp; </button>");

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

	$to->startTag("table");
	$to->startTag("tr");
	
	$min_file = fopen("min.txt", "r");
	$min_ini = readini($min_file);
	fclose($min_file);

	$n = $min_ini['survey']['count'];

	$fmap = [];
	$pros = [];
	
	$survs = collect_stapel_all($pid);
	$tot = collect_sum_diff($survs, ["positivitet", "akta", "relevans", "arlig", "tillit", "omdome", "motivation", "goal", "genomforande"]);
	
	
	// function ROD( $db, $id_n, $id_v, $key, $def ) // Read Or Default
	
	$sty  = ROD('data', ['pers', 'type', 'value_a'], [$pid, 301, 0], 'value_b', 0);
	$sty += ROD('data', ['pers', 'type', 'value_a'], [$pid, 302, 0], 'value_b', 0);
	$sty += ROD('data', ['pers', 'type', 'value_a'], [$pid, 300, 1], 'value_b', 0);
	$sty += ROD('data', ['pers', 'type', 'value_a'], [$pid, 300, 2], 'value_b', 0);
	$sty += ROD('data', ['pers', 'type', 'value_a'], [$pid, 300, 3], 'value_b', 0);
	$sty += ROD('data', ['pers', 'type', 'value_a'], [$pid, 300, 4], 'value_b', 0);


	for ($i=1; $i<=$n; ++$i)
	{
		$key = $i . ".filter";
		$flt = $min_ini['survey'][$key];

		$key = $i . ".button";
		$btn = $min_ini['survey'][$key];

		$key = $i . ".special";
		$spc = rwd($min_ini, 'survey', $key, '');
		
		if ($spc=='utv') $btn .= " +" . $tot;
		
		if ($spc=='sty') $btn =. " " . sprintf("%.1f", ($sty/6.0)) . "%";

		$key = $i . ".pro";
		$pro = rwd($min_ini, 'survey', $key, false);

		$pros[$flt] = $pro;
		$tit[$flt] = $btn;
		$fmap[$flt] = $i;
	}

	$nb2 = "&nbsp;&nbsp;";

	for ($i=0; $i<$n; ++$i) {
		
		if ($n>8) {
			$half = (int) (($n+1) / 2);
			if ($i == $half) {
				$to->stopTag("tr");
				$to->startTag("tr");
			}
		}

		$to->startTag("td");

		$base = "<button class='ilbbaicl' ";
		$base .= "style=' border-radius: 9px; ";
		$key = $fmap[$i] . ".color";
		if (array_key_exists($key, $min_ini['survey'])) {
			$base .= "background-color:" . $min_ini['survey'][$key] ."; ";
		}

		$fbn = $nb2 .  $tit[$i] . $nb2;
		$pro = $pros[$i];
		if ($pro !== false) {
			$row = data_last("SELECT * FROM data WHERE pers=$pid AND type=$pro");
			if ($row !== false)
				$fbn .= ' ' . $row['value_a'] . '&nbsp;%';
		}
		if ($at == $i) {
			$base .= "border-style:inset;'";
			$to->regLine($base . " > " . $fbn . " </button>");
		} else {
			$base .= "'";
			$to->regLine($base . " onclick='newpage(".$i.")' > " . $fbn . " </button>");
		}

		$to->stopTag("td");
	}

	$to->stopTag("tr");

	$to->stopTag("table");

	$to->scTag("hr");

	ptbl($to, $prow, $mynt);

	$alldata = roundup($data->pnr, $data->pid, $data->name, true);

	if ($at != '')
	{
		$to->scTag("hr");
		$to->startTag("div", "style='margin-left: 25px;'");

		$n = $cnt = $min_ini['survey']['count'];
		
		for ($i=1; $i<=$cnt; ++$i)
		{
			$key = $i . ".filter";
			$ff = $min_ini['survey'][$key];
			if ($ff != $at) continue;

			$key = $i . ".namn";
			$tit = rwd($min_ini, 'survey', $key, '');
			$to->regLine("<h1> " . $tit . " </h1> ");

			$key = $i . ".minor";
			$min = rwd($min_ini, 'survey', $key, false);
			if ($min)
				$to->regLine("<h5 class='normal' > " . $min . " </h5> ");

			$key = $i . ".ext";
			$ext = rwd($min_ini, 'survey', $key, false);

			if ($ext) {
				$key = $i . ".surv";
				$val = $min_ini['survey'][$key];
				//echo "Surv : " . $val . " - " . to_link($alldata, $val) . " <br>" . $eol;
				$lnk = $val; // to_link($alldata, $val) . "&returnto=nymin";
				$key = $i . ".surv";
				$do_pnr = rwd($min_ini, 'survey', $key, false);
				if ($do_pnr)
					$lnk = addKV($lnk, 'pnr', $data->pnr);
				$do_pid = rwd($min_ini, 'survey', $i.".pid" , false);
				if ($do_pid)
					$lnk = addKV($lnk, 'pid', $data->pid);
				
				debug_log('survey link : ' . $lnk);
				
				$to->regLine("<a href='$lnk'> <button> G&ouml;r Testet </button> </a> <br /> "); 
			}

			$key = $i . ".surv";
			$val = rwd($min_ini, 'survey', $key, false);

			if ($val && !$ext) {
				//echo "Surv : " . $val . " - " . to_link($alldata, $val) . " <br>" . $eol;
				$lnk = to_link($alldata, $val) . "&returnto=$RETURNTO";
				debug_log('survey link : ' . $lnk);
				$to->regLine("<a href='$lnk'> <button> G&ouml;r Testet </button> </a> <br /> ");

				$key = $i . ".result";
				$val = $min_ini['survey'][$key];
				//echo "Res : " . $val . " - " . to_link($alldata, $val) . " <br>" . $eol;
				$lnk = to_link($alldata, $val) . "&returnto=$RETURNTO";
				debug_log('result link : ' . $lnk);
				$to->regLine("<a href='$lnk'> <button> Se Resultat </button> </a> <br /> ");
			}

			$key = $i . ".embed";
			$emb = rwd($min_ini, 'survey', $key, false);

			if ($emb) {
				$lnk = addKV($emb, "pnr", $data->pnr);
				$do_pid = rwd($min_ini, 'survey', $i.".pid" , false);
				if ($do_pid)
					$lnk = addKV($lnk, 'pid', $data->pid);
				debug_log('embed link : ' . $lnk);
				$to->scTag('embed', 'type="text/html" width="100%" height="1500px" src="' . $lnk . '"');
			}
			
		}

		if ($at == 3) // stress
		{
			survOut($to, 101, 3);
		}
			
		if ($at == 8) // motivation
		{
			survOut($to, 102, 8);
		}

		if ($at == 10) // Kommunikation
		{
			survOut($to, 103, 10);
		}

		if ($at == 11) // Målsattning
		{
			survOut($to, 104, 11);
		}

		if ($at == 9) // Samarbete
		{
			survOut($to, 105, 9);
		}

		$to->stopTag('div');
	}


	$to->stopTag('div');

	if (getparam("sticp", "0") == "1") {
		$to->regLine('<div id="alt" class="xxx" state="0" >');
		$to->regLine(getCP($data));
		$to->regLine('</div>');
	} else {
		$to->regLine('<div id="alt" class="xxx"></div>');
	}

	echo <<<EOT

	<!-- Start of LiveAgent integration script: Chat button: Chattknapp -->
	<script type="text/javascript">
	(function(d, src, c) { var t=d.scripts[d.scripts.length - 1],s=d.createElement('script');s.id='la_x2s6df8d';s.defer=true;s.src=src;s.onload=s.onreadystatechange=function(){var rs=this.readyState;if(rs&&(rs!='complete')&&(rs!='loaded')){return;}c(this);};t.parentElement.insertBefore(s,t.nextSibling);})(document,
	'https://emperator.liveagent.se/scripts/track.js',
	function(e){ LiveAgent.createButton('d7903992', e); });
	</script>
	<!-- End of LiveAgent integration script -->

EOT;


	$to->stopTag('body');
}

$local = "./";
$common = "./";

index($local, $common);

?> 

</html>

