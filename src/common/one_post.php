
<!-- inlude one_post.php -->

<?php

include_once 'process_cmd.php';
include_once 'cmdparse.php';
include_once 'progress.php';
include_once 'debug.php';

function ndq($str)
{
	$in = false;
	$out = "";
	$n = strlen($str);
	$i = 0;
	while ($i<$n) {
		$c = $str[$i];
		++$i;
		if ( ($c == '"') || ($c == "'") ) {
			$in = ! $in;
			if ($in)
				$out .= "``";
			else
				$out .= "´´";
		} else {
			$out .= $c;
		}
	}
	return $out;
}

function addKV($lnk, $k, $v)
{
	if (strpos($lnk, '?')===false)
		return $lnk . '?' . $k . '=' . $v;
	else
		return $lnk . '&' . $k . '=' . $v;
}

function getCP($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/minsida.php?noside=true';
	$cp_have = false;
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
	$cp_have = false;
	if ($data->pid!=0) {
		$cp_site .= $cp_have ? "&" : "?";
		$cp_have = true;
		$cp_site .= "pid=" . $data->pid;
	}
	if ($data->pnr!=0) {
		$cp_site .= $cp_have ? "&" : "?";
		$cp_have = true;
		$cp_site .= "pnr=" . $data->pnr;
	}
	return ' <iframe src="' . $cp_site . '" style="min-height:100vh;width:100%" frameborder="0" > ';
}

function getUtb($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/utbildning.php';
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

function index($styr, $local, $common)
{
	debug_log('index() in one_post.php');

	global $emperator;

	$to = new tagOut;
	
	$data = new Data;
	
	$data->returnto = getparam('returnto', false);

	$data->snum = getparam("seg", "1");

	$seg = 'segment-' . $data->snum;

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

	$atq = getparam("atq", 0);

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

	$bnum = 1;
	$maxseg = 1;
	$curr = '';

	$cmdlst = [];
	
	$title = 'Utbildning';
	
	while (true) {
		++$data->lineno;
		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$cmd = cmdparse($buffer);
		if ($cmd->is_empty) continue;
		if ($cmd->is_command) {
			if ($cmd->command == 'batt') {
				$bnum = (int)$cmd->rest;
				continue;
			}
			if ($cmd->command == 'max') {
				$maxseg = (int)$cmd->rest;
				continue;
			}
		}

		if ($cmd->is_segment) {
			$curr = $cmd->segment;
			continue;
		}

		if ($curr != $seg) continue;
		
		if ($cmd->is_command)
			if ($cmd->command == 'title')
				$title = $cmd->rest;

		$cmdlst[] = $cmd;
	}
	
	$data->bnum = $bnum;
	$data->name = $name;
	$data->mynt = $mynt;
	$data->max = $maxseg;

	echo '<!-- ' . 'set bnum to ' . $data->bnum . ' -->';

	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . $eol;

	$to->startTag('script');
	$to->regLine('function doOne() {');
	$to->regLine('  var canvas = document.getElementById("myCanvas");');
	$to->regLine('  setProgress(0, canvas);');

	$to->regLine('  showTime();');
	$to->regLine('  div1 = document.getElementById("OneBtn");');
	$to->regLine('  div1.style.visibility = "hidden";');

	$to->regLine('  div = document.getElementById("QueryDivider");');
	$to->regLine('  s = "<br> <br>";');

	$qi = 0;
	$qcmd = (object)[];
	foreach ($cmdlst as $value) {
		if ($value->is_command) {
			if ($value->command == 'query') {
				++$qi;
				if ($qi == 1) {
					$qcmd = $value;
					break;
				}
			}
		}
	}

	if ($qi > 0) {
		if (is_array($qcmd->params)) {
			$n = count($qcmd->params);
			$to->regLine('  s += "<h3> ' . ndq($qcmd->params[0]) . ' </h3> <br><br>";');
			for ($i=1; $i<$n; ++$i) {
				$s = $qcmd->params[$i];
				$s = trim($s);
				$corr = false;
				if ($s[0] == '_') {
					$s = substr($s, 1);
					$corr = true;
				}
				$ss = "<div class='btndiv' id='b" . $i . "'> <image src='../common/blank.png'> </div>";		
				$to->regLine('  s += "&nbsp; &nbsp; &nbsp; <button onclick=\'setA(2, ' . $i . ', ' . ($corr?"true":"false") . ')\'> ' . $ss . ' <font size=\'+2\'> ' . $s .
				  ' &nbsp; &nbsp; &nbsp; </font> </button> <br><br>";');
			}

			$to->regLine('  ab = document.getElementById("AudioBox"); if (ab) ab.play();');
			$to->regLine('  ss = (new Date()).getTime().toString();');
			$to->regLine('  document.getElementById("TimeStart").value = ss;');
			$to->regLine('  setInterval(showTime, 150);');
		}
	}

	$to->regLine('  div.innerHTML = s;');
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

	$qi = 0;
	$qcmd = (object)[];
	$qn = 0;
	foreach ($cmdlst as $value) {
		if ($value->is_command) {
			if ($value->command == 'query')
				++$qn;
		}
	}

	$to->regLine('function setA(i, a, corr) {');

	$to->regLine('  var canvas = document.getElementById("myCanvas");');
	$to->regLine('  setProgress(Math.round((100.0 * (i-1))/' . $qn . '), canvas);');

	$to->regLine('  var audio;');
	$to->regLine('  if (corr) ');
	$to->regLine('    audio = new Audio("../common/corr.mp3"); ');
	$to->regLine('  else ');
	$to->regLine('    audio = new Audio("../common/err.mp3"); ');
	$to->regLine('  audio.play(); ');

	$to->regLine('  bd = document.getElementById("b" + a.toString());');
	$to->regLine('  bd.innerHTML = "<image src=\'../common/" + ((corr)?"corr":"err") + ".png\' >";');

	$to->regLine('  div = document.getElementById("QueryDivider");');
	$to->regLine('  s = "<br> <br>";');
	$to->regLine('  f = true;');
	$to->regLine('  switch (i) {');

	foreach ($cmdlst as $value) {
		if ($value->is_command) {
			if ($value->command == 'query') {
				++$qi;
				if ($qi > 1) {
					$to->regLine('    case ' . $qi . ':');
					$to->regLine('      s += "<h3> ' . ndq($value->params[0]) . ' </h3> <br><br>";');
					$n = count($value->params);
					for ($i=1; $i<$n; ++$i) {
						$s = $value->params[$i];
						$s = trim($s);
						$corr = false;
						if ($s[0] == '_') {
							$s = substr($s, 1);
							$corr = true;
						}
						$ss = "<div class='btndiv' id='b" . $i . "'> <image src='../common/blank.png'> </div>";
						$to->regLine('      s += "&nbsp; &nbsp; &nbsp; <button onclick=\'setA(' . ($qi+1) . ', ' . $i . ', ' . ($corr?"true":"false") . ')\'> ' . $ss . ' <font size=\'+2\'> ' . $s .
						  ' &nbsp; &nbsp; &nbsp; </font> </button> <br><br>";');
					}
					$to->regLine('      break;');
				}
			}
		}
	}

	++$qi;
	$to->regLine('    case ' . $qi . ':');
	$to->regLine('      f = false;');
	$to->regLine('      break;');

	$to->regLine('  }');

	$to->regLine('  setTimeout(function(){ div.innerHTML = s; }, 750);');

	$to->regLine('  div2 = document.getElementById("AnswerDiv");');
	$to->regLine('  ans = "\'" + a.toString() + "\'";');
	$to->regLine('  nam = "\'q" + (i-1).toString() + "\'";');
	$to->regLine('  div2.innerHTML += "<input type=\'hidden\' value=" + ans + " id=" + nam + " name=" + nam + " />";');
	$to->regLine('  if (!f) {');
	$to->regLine('    document.getElementById("TimeStop").value = (new Date()).getTime().toString();');
	$to->regLine('    setTimeout(function(){ document.getElementById("myForm").submit();}, 1250); ');
	$to->regLine('  }');

	$to->regLine('}');

	$to->stopTag('script');
	
	// $to->startTag('style');
	// $to->regLine('.navbarb {');
	// $to->regLine('  overflow: scroll;');
	// $to->regLine('  position: fixed;');
	// $to->regLine('  bottom: 0;');
	// $to->regLine('  width: 100%;');
	// $to->regLine('}');
	// $to->regLine('.navbarb embed {');
	// $to->regLine('  background-color: coral');
	// $to->regLine('  float: left;');
	// $to->regLine('  display: block;');
	// $to->regLine('}');
	// $to->stopTag('style');

	echo '<title>' . $title . '</title>' . $eol;
	echo '</head>' . $eol;
	$to->startTag('body');
	
	if (getparam("debug")=="true")
	{
		$to->startTag('div');
		$to->regLine('<hr>');
		$qii = 0;
		foreach ($cmdlst as $value) {
			if ($value->is_command) {
				if ($value->command == 'query') {
					++$qii;
					$to->regLine('Query ' . $qii . " <br>");
					$to->regLine('-- query text : ' . ndq($value->params[0]) . " <br>");
					$n = count($value->params);
					for ($i=1; $i<$n; ++$i) {
						$s = $value->params[$i];
						$s = trim($s);
						$corr = false;
						if ($s[0] == '_') {
							$s = substr($s, 1);
							$corr = true;
						}
						$to->regLine('-- answer ' . $i . ' : ' . ($corr?"(ok) ":"") . ndq($s) . " <br>");
					}
					$to->regLine('<hr>');
				}
			}
		}
		$to->stopTag('div');
	}

	if ($atq == 0)
	{
		$side = fopen("styrkant.txt", "r") or die("Unable to open file!");

		$to->startTag ('div', 'class="sidenav"');
		$to->startTag ('div', 'class="indent"');

		$to->startTag ('div');
		
		$to->regLine("<button id='BtnSett' onClick='doChangeC()'> Settings </button>");
		
		if (getparam("sticp", "0") == "1") {
			$to->regLine("<button id='BtnCP'  onClick='doChangeB()'> Min Sida </button>");
		} else {
			$to->regLine("<button id='BtnCP' onClick='doChangeB()'> Min Sida </button>");
		}

		$to->regLine("<br class='hs'> <button id='BtnUtb' style='background-color:#5E5;font-size:15px;' onClick='doChangeD()'> &nbsp;Till utbildningen&nbsp; </button>");

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

		fclose($side);

		$to->startTag('div', 'id="main" class="main"');

		foreach ($cmdlst as $cmd) {

			if ($cmd->is_command) {
				$w = process_cmd($to, $data, $cmd->command, $cmd->params);
				if (!($w===true))
					echo $w;
			} else if ($cmd->is_text) {
				$to->regLine($cmd->text);
			}
		}
		$to->stopTag('div');

	} else {

		$to->regLine('<br> <br>');

		$to->startTag('form', 'id="myForm" action="score.php" method="GET"');

		$to->scTag('input', 'type="hidden" value="' . $data->snum . '" id="seg"  name="seg"' );
		$to->scTag('input', 'type="hidden" value="' . $data->pnr  . '" id="pnr"  name="pnr"' );
		$to->scTag('input', 'type="hidden" value="' . $pid        . '" id="pid"  name="pid"' );
		$to->scTag('input', 'type="hidden" value="' . $name       . '" id="name" name="name"');
		$to->scTag('input', 'type="hidden" value="' . ($atq+1)    . '" id="atq"  name="atq"' );

		if ($atq > 1) {
			for ($ii=1; $ii<$atq; ++$ii) {
				$qn = 'q' . $ii;
				$qq = getparam($qn, 0);
				$to->scTag('input', 'type="hidden" value="' . $qq . '" id="' . $qn . '" name="' . $qn . '"');
			}
		}

		$qi = 0;
		$qcmd = (object)[];
		$found = false;
		foreach ($cmdlst as $value) {
			if ($value->is_command) {
				if ($value->command == 'query') {
					++$qi;
					if ($qi == $atq) {
						$qcmd = $value;
						$found = true;
						break;
					}
				}
			}
		}

		if ($found) {
			$to->scTag('input', 'type="hidden" value="' . -1 . '" id="q' . $atq . '" name="q' . $atq . '"');

			$n = count($qcmd->params);

			$to->regLine('<h1> ' . $qcmd->params[0] . ' </h1> <br>');

			for ($i=1; $i<$n; ++$i) {
				$s = $qcmd->params[$i];
				$s = trim($s);
				if ($s[0] == '_')
					$s = substr($s, 1);

				$to->regLine('&nbsp; &nbsp; &nbsp; <button onclick="setA(' . $i . ')"> <font size="+3"> ' . $s . ' </font> </button>');
			}
		} else {
			//$to->regLine('<input type="submit" value="Klar" >');
		}

		$to->stopTag('form');
	}

	if (getparam("sticp", "0") == "1") {
		$to->regLine('<div id="alt" class="xxx" state="0" >');
		$to->regLine(getCP($data));
		$to->regLine('</div>');		
	} else {
		$to->regLine('<div id="alt" class="xxx"></div>');
	}

	$to->stopTag('body');
}

?> 
