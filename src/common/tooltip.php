
<?php

function tooltip($to, $data)
{
	

	$to->startTag("table");
	$to->regLine("<tr> <td> <img src='corr.png'  />  </td><td>  Värdegrund        </td> </tr> ");
	$to->regLine("<tr> <td> <img src='corr.png'  />  </td><td>  Missionstatement  </td> </tr> ");
	$to->regLine("<tr> <td> <img src='heret.png' />  </td><td>  Utveckling        </td> </tr> ");
	$to->regLine("<tr> <td> <img src='blank.png' />  </td><td>  Disk Analys       </td> </tr> ");
	$to->stopTag("table");
}

?>

