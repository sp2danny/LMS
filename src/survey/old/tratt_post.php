
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

    $kn = get_styr($styr, 'querys', 'kat', $variant);
	
    $kv = [];
    $km = [];

    for ($i = 1; $i <= $kn; ++$i)
    {
        $kv[$i] = 0;
        $km[$i] = 0;
	}

    $nn = get_styr($styr, 'querys', 'num', $variant);
	
	echo get_styr($styr, 'summary', 'text', $variant);

    for ($i = 1; $i <= $nn; ++$i)
    {
        $v = getparam('q' . $i);

        $k = get_styr($styr, 'querys', 'query.' . $i . '.kat', $variant);
        $w = get_styr($styr, 'querys', 'query.' . $i . '.weight', $variant);

        $kv[$k] += $w * $v;
        $km[$k] += $w * 100;
		
    }

	echo "<table>";
	$max = 0;
	for ($i = 1; $i <= $kn; ++$i)
	{
		echo "<tr>";
		$val = 100.0 * $kv[$i] / $km[$i];
		if ($val > $max)
			$max = $val;
		echo "<td>";
		echo get_styr($styr, 'querys', "kat.$i.name", $variant);
		echo "</td><td>";
		echo round($val) . "%";
		echo "</td></tr>";
	}
	echo "</table>" . "\n";
	
	$lid = getparam('lid');
	$url = "result.php?lid=$lid&val=$max";
	
	echo "<a href='$url' >";
	echo "<button> ";
	echo get_styr($styr, 'summary', 'button', $variant);
	echo " </button> </a>";

	//echo '<meta http-equiv = "refresh" content = ';
	//echo '"' . "0; URL='result.php?lid=" . getparam('lid') . "&val=" . $max . "'" . '"' . " />" . "\n";

?>


</head>
<body>
</body>
</html>



