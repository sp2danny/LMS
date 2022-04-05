
<!-- inlude process_cmd.php -->

<?php

class Data
{
	public $qnum = 0;
	public $snum = 0;
	public $pnr = '';
	public $corr = [];
	public $lineno = 0;
	public $inq = false;
}

function process_cmd($to, $data, $cmd, $args)
{
	$eol = "\n";
	
	$ret = true;

	switch ($cmd)
	{
		case 'qstart':
			$to->startTag('div', 'id="QueryDivider"');
			break;
		case 'qstop':
			$to->stopTag('div');
			break;
		case 'text':
			if ($data->inq)
				$to->regLine('<tr> <td colspan="2"> ' . $args[0] . ' </td> </tr>');
			else
				$to->regLine($args[0]);
			break;
		case 'sound':
			$to->startTag('audio', 'controls');
			$to->regLine('<source src="' . $args[0] . '" type="audio/mp3">');
			$to->stopTag('audio');
			break;
		case 'header':
			$to->regLine('<h1>' . $args[0] . '</h1>');
			break;
		case 'line':
			$to->regLine('<hr color="' . $args[0] . '" />');
			break;
		case 'break';
			$ss = '';
			for ($i=0; $i<$args[0]; ++$i)
				$ss = $ss . '<br /> ';
			$to->regLine($ss);
			break;
		case 'image':
			if (count($args)>1) {
				$to->regLine('<img width=' . $args[0] . '%  src="' . $args[1] . '" /> <br />');
			} else {
				$to->regLine('<img src="' . $args[0] . '" /> <br />');				
			}
			break;
		case 'embed':
			if (count($args)>1) {
				$to->regLine('<iframe ' . $args[0] . '  src="' . $args[1] . '" /> </iframe> <br />');
			} else {
				$to->regLine('<iframe src="' . $args[0] . '" /> </iframe> <br />');	
			} 
			break;
		case 'begin':
			$data->inq = true;
			if (count($args) != 4) {
				$ret  = ' *** WARNING *** <br />' . $eol;
				$ret .= ' malformed "begin" command on line ' . $data->lineno . ', needs 4 parameters <br />' . $eol;
				$ret .= ' *** WARNING *** <br />' . $eol;
				$args = explode(',', 'Starta, score.php, 130, lugn.mp3');
			}
			$to->regLine('<button id="StartBtn" onclick="doShow()"> ' . trim($args[0]) . ' </button> <br />');
			$to->startTag('div', 'id="QueryBox" style="display:none;"');
			$to->regLine('<audio id="AudioBox" preload loop> <source src="' . trim($args[3]) . '" type="audio/mp3"></audio>');
			$to->startTag('form', 'action="' . trim($args[1]) . '" method="GET"');
			$to->scTag('input', 'type="hidden" value="' . $data->snum . '" id="seg" name="seg"');
			$to->scTag('input', 'type="hidden" value="' . $data->pnr . '" id="pnr" name="pnr"');
			$to->scTag('input', 'type="hidden" value="" id="TimeStart" name="timestart"');
			$to->scTag('input', 'type="hidden" value="" id="TimeStop" name="timestop"');
			$to->scTag('input', 'type="hidden" value="' . trim($args[2]) . '" id="TimeMax" name="timemax"');
			$to->scTag('input', 'type="hidden" value="0" id="Score" name="score"');
			$to->startTag('table');
			break;
		case 'one':
			$data->inq = true;
			$to->regLine('<button id="OneBtn" onclick="doOne()"> ' . trim($args[0]) . ' </button> <br />');

			$to->startTag('form', 'id="myForm" action="' . trim($args[1]) . '" method="GET"');
			$to->scTag('input', 'type="hidden" value="' . $data->snum . '" id="seg" name="seg"');
			$to->scTag('input', 'type="hidden" value="' . $data->pnr . '" id="pnr" name="pnr"');
			$to->scTag('input', 'type="hidden" value="0" id="TimeStart" name="timestart"');
			$to->scTag('input', 'type="hidden" value="0" id="TimeStop" name="timestop"');
			$to->scTag('input', 'type="hidden" value="' . trim($args[2]) . '" id="TimeMax" name="timemax"');
			$to->scTag('input', 'type="hidden" value="0" id="Score" name="score"');
			$to->startTag('div', 'id="AnswerDiv"');
			$to->stopTag('div');
			$to->stopTag('form');
			$ab = trim($args[3]);
			if (!empty($ab)) {
				$to->startTag('audio', 'id="AudioBox"');
				$to->scTag('source', 'src="' . trim($args[3]) . '" ');
				$to->stopTag('audio');
			}
			break;
		case 'onestop':
			break;
		case 'video':
			$to->regLine('<iframe width="1280" height="720" src="https://player.vimeo.com/video/' . $args[0] . '"  frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>');
			break;
		case 'query':
			$data->qnum++;
			$valnum = 0;
			
			// $to->regLine('<tr height="25px"> <td colspan="2"> <B>' . $args[0] . ' </B> </td> </tr>');
			// $to->startTag('tr height="45px"');
			// $to->regLine('<td width="45px" > <img id="' . 'QI-' . $data->qnum . '" src="../common/blank.png" /> </td>');
			// $to->startTag('td');
			
			$n = count($args);
			for ($i=1; $i<$n; ++$i) {
				$ss = trim($args[$i]);
				if ($ss && $ss[0] == '_') {
					$ss = substr($ss, 1);
					$data->corr[$data->qnum] = $valnum;
				}
				//if ($valnum > 1) $to->regLine('<br />');
				//$to->regLine('<input type="radio" id="' . 'QR-' . $data->qnum . '" name="' . $data->qnum . '" value="' . $valnum . '" />' . $ss . '');
				$valnum++;
			}
			//$to->stopTag('td');
			//$to->stopTag('tr');
			//$to->regLine('<tr><td> &nbsp; </td></tr>');
			break;
			
		case 'submit':
			// submit
			// Rätta, Klar
			$data->inq = false;
			
			if (count($args) != 2) {
				$ret  = ' *** WARNING *** <br />' . $eol;
				$ret .= ' malformed "s" command on line ' . $data->lineno . ', needs 2 parameters <br />' . $eol;
				$ret .= ' *** WARNING *** <br />' . $eol;
				$args = explode(',', 'Rätta, Klar');
			}

			$to->stopTag('table');
			$to->startTag('script');
			$to->regLine('function doCorr() {' );
			$to->regLine('  document.getElementById("TimeStop").value = (new Date()).getTime().toString();');
			$to->regLine('  var scr = 0;');
			for ($idx = 1; $idx <= $data->qnum; ++$idx) {
				$to->regLine('  if( corr1(' . $idx . ', ' . $data->corr[$idx] . ')) scr += 1;');
			}
			$to->regLine('  document.getElementById("SubmitBtn").style.display = "block";');
			$to->regLine('  document.getElementById("CorrBtn").style.display = "none";');
			$to->regLine('  document.getElementById("Score").value = scr.toString();');

			$to->regLine('}');
			$to->stopTag('script');

			$to->regLine('<input id="SubmitBtn" type="submit" value="' . $args[1] . '" style="display:none;" /> <br />');
			$to->stopTag('form');
			$to->regLine('<button  id="CorrBtn" onclick="doCorr()">' . $args[0] . '</button> <br />');
			$to->stopTag('div');
			
			break;
			
		case 'next':
			// next
			$to->startTag('button', 'onclick="location.href=' . "'" . 'index.php?seg=' . ($snum+1) . "'" . '" type="button"');
			$to->regLine($args[0]);
			$to->stopTag('button');
			break;
			

		case 'discquery':
			$to->regLine( ' <embed type="text/html" src="../common/discquery.php" > ' );
			break;
		case 'discdisplay':
			$to->regLine( ' <embed type="text/html" src="../common/discdisplay.php" > ' );
			break;


		default:
			$ret  = ' *** WARNING *** <br />' . $eol;
			$ret .= ' unrecognized command : "' . htmlspecialchars($cmd) . '" on line ' . $data->lineno . ' <br />' . $eol;
			$ret .= ' *** WARNING *** <br />' . $eol;
	}
	
	return $ret;
}

?>

