
<?php
include 'head.php';
include 'common.php';
?>

</head>
<body>

<table>
<tr>

<?php

$pnr = getparam('pnr');
$bnum = getparam('bnum');

for ($i=1; $i<=5; ++$i) {
	echo " <td> ";
	echo " <a href='" ;
	echo "dorate.php?score=" . $i;
	echo "&pnr=" . $pnr;
	echo "&bnum=" . $bnum;
	echo "' > <img src='sc" . $i . ".png' /> </a> </td> \n";
}

?>

</tr>
</table>

</body>
</html>
