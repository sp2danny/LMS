
<?php

include_once 'connect.php';
include_once 'common_php.php';
include_once 'tagOut.php';
include_once 'getparam.php';

function getpid()
{
	return getparam('pid', 16);
}


function ptbl($to, $prow, $score=0)
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

	global $emperator;

	$query = 'SELECT * FROM data WHERE pers=' . getpid() . ' AND type=4';
	$res = mysqli_query($emperator, $query);
	if ($row = mysqli_fetch_array($res)) {
		$mynt = $row['value_a'];
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




$to = new tagOut;


$pid = getpid();
$query = "SELECT * FROM pers WHERE pers_id=$pid";
$res = mysqli_query( $emperator, $query );
if ($res) if ($row = mysqli_fetch_array($res))
{
}

ptbl($to, $row);

?>

