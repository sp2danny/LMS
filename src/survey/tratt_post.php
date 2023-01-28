
<?php

include "common.php";
include "connect.php";

$styr = LoadIni("styr.txt");

$eol = "\n";

?>

<!DOCTYPE html>

<html>

<head>

  <?php

    $kn = $styr['querys']['kat'];
	
    $kv = [];
    $km = [];

    for ($i = 1; $i <= $kn; ++$i)
    {
        $kv[$i] = 0;
        $km[$i] = 0;
	}

    $nn = $styr['querys']['num'];

    for ($i = 1; $i <= $nn; ++$i)
    {
        $v = getparam('q' . $i);

        $k = $styr['querys']['query.' . $i . '.kat'];
        $w = $styr['querys']['query.' . $i . '.weight'];

        $kv[$k] += $w * $v;
        $km[$k] += $w * 100;
    }

	$max = 0;
	for ($i = 1; $i <= $kn; ++$i)
	{
		$val = 100.0 * $kv[$i] / $km[$i];
		if ($val > $max)
			$max = $val;
	}

	echo '<meta http-equiv = "refresh" content = ';
	echo '"' . "0; URL='result.php?lid=" . getparam('lid') . "&val=" . $max . "'" . '"' . " />" . "\n";

?>


</head>
<body>
</body>
</html>



