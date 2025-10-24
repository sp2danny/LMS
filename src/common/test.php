
<?php

include_once 'head.php';
include_once 'common.php';
include_once 'tagOut.php';
include_once 'connect.php';
include_once 'util.php';
include_once 'process_cmd.php';



function getNxt($data) {
	$nxt_site = 'https://www.mind2excellence.se/site/common/forward.php';
	if ($data->pnr!=0) {
		$nxt_site = addKV($nxt_site, 'pnr', $data->pnr);
	}
	if ($data->bnum!=0) {
		$nxt_site = addKV($nxt_site, 'ob', $data->bnum);
	}
	if ($data->snum!=0) {
		$nxt_site = addKV($nxt_site, 'os', $data->snum);
	}
	return $nxt_site;
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







function index($local, $common)
{
	global $RETURNTO;

	debug_log("index() in $RETURNTO.php");

	global $emperator;

	$to = new tagOut;
	$to->bump(2);
	
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

	$title = 'Test Sida';

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

	button.big3 {
	  font-size: 12px;
	  font-weight: regular;
	  width: 155px;
	  height: 25px;
	  border-radius: 7px;
	  background-color: #96BF0D;
	  color: black;
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



	br.hs {
		line-height: 9px;
	}

</style>

EOT;


	$to->startTag('script');
	
	$to->regLine('function doGoNext() { ');
	$to->regLine("  window.location.href = '" . getNxt($data) . "'; ");
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

	$side = fopen("styrkant.txt", "r") or die("Unable to open file!");


	{ // side
		$to->startTag ('div', 'class="sidenav"');
		$to->startTag ('div', 'class="indent"');

		$to->startTag ('div');
		
		
		$to->regLine("<button class='big3' id='BtnCP' onClick='doChangeB()'> Min Egen Sida </button> <br>");

		$to->regLine("<br> ");
		
		$to->regLine("<button class='big3' id='BtnUtb' onClick='doChangeD()'> &nbsp;Utbildningsportalen&nbsp; </button>");

		$to->regLine("<br> ");
	
		$to->regLine("<button class='big3' id='BtnNxt' onClick='doGoNext()'> &nbsp;Forts&auml;tt utbildningen&nbsp;&gt;&gt; </button>");

		$to->regLine("<br> ");

		//if (is_in($data->tag,"mentor"))
		//	$to->regLine("<br class='hs'> <button id='BtnMnt' style='background-color:" . $eg . ";font-size:15px;' onClick='doChangeMnt()'> &nbsp;Mentor&nbsp; </button>");

		$to->regline  ('<hr>');
		$to->stopTag  ('div');


		$to->stopTag('div');
		$to->stopTag('div');

	}

	fclose($side);

	$to->startTag('div', 'id="main" class="main"');
	
	$to->startTag('div', 'id="lbl"');
	$to->stopTag('div');
	$to->scTag('br');
	

	$to->startTag('div');
	$to->scTag('br');
	$to->scTag('img', 'width=50% src="logo.png"');

	$grp = $data->grp;

	$to->stopTag('div');


	echo <<<EOT

	<!-- Start of LiveAgent integration script: Chat button: Chattknapp -->
	<script type="text/javascript">
	(function(d, src, c) { var t=d.scripts[d.scripts.length - 1],s=d.createElement('script');s.id='la_x2s6df8d';s.defer=true;s.src=src;s.onload=s.onreadystatechange=function(){var rs=this.readyState;if(rs&&(rs!='complete')&&(rs!='loaded')){return;}c(this);};t.parentElement.insertBefore(s,t.nextSibling);})(document,
	'https://emperator.liveagent.se/scripts/track.js',
	function(e){ LiveAgent.createButton('d7903992', e); });
	</script>
	<!-- End of LiveAgent integration script -->

EOT;

	$to->stopTag('div');

	$to->stopTag('body');
}


$local = "./";
$common = "./";

index($local, $common);


?> 

</html>
