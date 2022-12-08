
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


	echo "Selected : <br> <br>" . $eol;

	$max = getparam('max');

	for ($i=1; $i<=$max; ++$i)
	{
		$val = getparam($i, 0);
		if ($val != 0)
		{
			echo $val . "<br>" . $eol;
		}
	}
}

all();

?>

</body>
</html>


