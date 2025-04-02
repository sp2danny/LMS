
<?php

function tooltip($to, $data)
{

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
}

?>

