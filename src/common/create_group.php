
<?php

include 'head.php';
include 'roundup.php';

echo <<<EOT

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
table tr td {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
}
</style>
EOT;

include 'common.php';
include 'connect.php';

$eol = "\n";

echo '</head><body>' . $eol;
echo '<br />' . $eol;
echo '<img width=50%  src="logo.png" /> <br />';
echo '<br /> <br />' . $eol;


function all()
{
	global $emperator, $eol;

	echo "<a href='create_report.php' > <button> Tillbaka </button> </a> <br> <br>" . $eol;

	$max = getparam('max');
	$grp = getparam('grp');
	
	$ok = true;

	for ($i=1; $i<=$max; ++$i)
	{
		$val = getparam($i, 0);
		if ($val != 0)
		{
			echo $val;
		} else {
			continue;
		}
		
		$pid = 0;
		$pnr = $val;
		
		$query = 'SELECT * FROM pers WHERE pnr="' . $val . '"';
		$res = mysqli_query($emperator, $query);
		if ($prow = mysqli_fetch_array($res)) {
			$pid = $prow['pers_id'];
			$name = $prow['name'];
			echo ' ' . $name . ' ';
		} else {
			echo " <not found> <br>" . $eol;
			continue;
		}
		
		$query = 'UPDATE pers SET grupp="' . $grp . '" WHERE pnr="' . $val . '"';
		$res = mysqli_query($emperator, $query);
		$ok = $ok && $res;

		echo "<br>" . $eol;
		
	}
	
	echo "<br><br> Sattes alla till gruppen " . $grp . "<br><br>" . $eol;
	
	if (!$ok)
		echo "<br> N&aring;got gick fel <br> " . $eol;
}

all();

?>

</body>
</html>


