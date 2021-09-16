

<?php

include 'php/head.php';

include 'php/common.php';

$eol = "\n";

echo '</head><body>' . $eol;

$dircont = scandir(".");

$batts = array();

foreach ($dircont as $key => $value) {
	if (strlen($value) < 5) continue;
	$a = substr($value, 0, 5);
	if ($a != 'batt-') continue;
	$a = substr($value, 5);
	$batts[] = $a;
}


$dagens = array();
$ord = fopen("ord.txt", "r");
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

echo '<img width=30%  src="logo.png"> <br>' . $eol;

echo '<form action="' . 'next.php' . '" method="GET">' . $eol;

echo '<br><br><label for="pnr">Personnummer:</label>' . $eol;
echo '<input type="text" id="pnr" name="pnr"><br><br>' . $eol;

echo '<h3>' . 'Fr&aring;gebatteri' . '</h3>' . $eol;
echo '<div class="form-group"><ol> ' . $eol;

foreach ($batts as $key => $value) {
	echo '<li> <input type="radio" name="' . 'batt' . '" value="' . $value . '" />' . $value . '</li>' . $eol;
}
echo '</ol></div>' . $eol;
echo '<input type="submit" value="' . 'Starta' . '">' . $eol;

echo '</form>' . $eol;

$n = count($dagens);
if ($n > 0) {
	$i = rand(0, $n-1);
	echo '<br /><br />' . $eol;
	echo '<center>' . $dagens[$i] . '</center>' . $eol;
}

?> 

</body>
</html>

