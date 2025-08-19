
<?php

include_once 'debug.php';

function collect_it($data)
{
	if ($data->grpsk === false)
	{
		$str = "Egenskattning av " . $data->pnr;
	} else {
		$str = "Gruppskattning av " . $data->pnr . " för " . $data->grpsk;
	}
	return $str;
}

function collect_it_2($data)
{
	$ret = [];
	$ret["by"] = $data->pnr;
	$ret["for"] = $data->grpsk;


	return $ret;
}

//	värdegrund  201          val
//	miss-stat   202          val


function tooltip($to, $data)
{

	//$to->regLine("&lt; db error &gt;");
	//return;


	$txt = ["Värdegrund", "Missionstatement", "Utveckling", "Disk Analys"];
	$fn = 2;
	$n = 4;

	$to->startTag("table");
	for ($i=0; $i<$n; ++$i) {
		$line = "<tr> <td> <img src=";
		if ($i <  $fn)  $line .= "'corr.png'";
		if ($i == $fn)  $line .= "'heret.png'";
		if ($i >  $fn)  $line .= "'blank.png'";
		$line .= " />  </td><td> ";
		$line .= $txt[$i];
		$line .= " </td> </tr> ";
		$to->regLine($line);
	}
	$to->stopTag("table");
	$to->scTag("br");
	$to->startTag("div", "style='font-size:12px' ");
	$to->regLine(collect_it($data));
	$to->stopTag("div");

}

?>

