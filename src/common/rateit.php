
<?php
include 'head.php';
include 'common.php';
?>

</head>
<body>


<?php

$pnr = getparam('pnr');
$bnum = getparam('bnum');
$snum = getparam('snum');
$maxs = getparam('maxs');

echo "<br><br><center>";

echo "<p> &Auml;r du n&ouml;jd med att ha klarat denna del i utbildningen? </p> <br> \n";

echo " <table> <tr> ";

for ($i=1; $i<=5; ++$i) {
	echo " <td> ";
	echo " <a href='" ;
	echo "dorate.php?score=" . $i;
	echo "&pnr=" . $pnr;
	echo "&bnum=" . $bnum;
	echo "&snum=" . $snum;
	echo "&maxs=" . $maxs;
	echo "' > <img src='sc_" . $i . ".png' /> </a> </td> \n";
}

?>

</tr>
</table>

</center>

<br><br>

<center> Du kan avsluta n&auml;rsomhelst, och forts&auml;tta fr&aring;n d&auml;r du var </center>

</body>
</html>
