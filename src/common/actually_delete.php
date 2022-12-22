
<?php

include 'head.php';

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

	$pid = getparam('pid');
	
	if ($pid == 0)
	{
		echo "failed.";
		return;
	}

	$ok = true;
	
	$query = 'DELETE FROM pers WHERE pers_id="' . $pid . '"';
	$res = mysqli_query($emperator, $query);
	echo "executed <code> " . $query . "</code> (" . $res . ") <br>" . $eol;
	$ok = $ok && $res;

	$query = 'DELETE FROM data WHERE pid="' . $pid . '"';
	$res = mysqli_query($emperator, $query);
	echo "executed <code> " . $query . "</code> (" . $res . ") <br>" . $eol;
	$ok = $ok && $res;
	
	$query = 'DELETE FROM surv WHERE pid="' . $pid . '"';
	$res = mysqli_query($emperator, $query);
	echo "executed <code> " . $query . "</code> (" . $res . ") <br>" . $eol;
	$ok = $ok && $res;

	if (!$ok)
		echo "<br> N&aring;got gick fel <br> " . $eol;
	else
		echo "<br> Delete Klar <br> " . $eol;

}

all();

?>

</body>
</html>


