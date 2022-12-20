
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
	
	echo "<h1> Delete User </h1> <br> <br>" . $eol;
	
	$query = 'SELECT * FROM pers';
	$res = mysqli_query($emperator, $query);
	if ($res) {
		while ($prow = mysqli_fetch_array($res)) {

			echo "<a href='delete_user.php?pid=" . $prow["pers_id"] . "' >";
			echo "<button>" . $eol;
			echo $prow["pnr"] . " " . $prow["name"] . $eol;
			echo "</button></a><br>" . $eol;
		}
	}
}

all();

?>

</body>
</html>


