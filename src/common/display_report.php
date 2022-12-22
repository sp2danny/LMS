
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

function GetQ($pid, $a) {
	global $emperator;
	$obj = new stdClass();

	$query = "SELECT * FROM data WHERE type=9 AND pers='" . $pid . "' AND value_a='" . $a . "'";

	$res = mysqli_query( $emperator, $query );
	if ($res) {
		$row = mysqli_fetch_array($res);
		if ($row) {
			$data_id = $row['data_id'];
			$obj->type = $row['value_b'];
			$obj->source = $row['value_c'];
			return $obj;
		}
	}
	return false;
}

function all()
{
	global $emperator, $eol;

	echo "<a href='create_report.php' > <button> Tillbaka </button> </a> <br> <br>" . $eol;


	echo "Selected : <br> <br>" . $eol;

	$max = getparam('max');

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
		
		$alldata = roundup($pnr, $pid, $name);
		$atnum = 0;
		$block_name = "";
		$line_name = "";
		
		foreach ($alldata as $block) {
			if (!$block->someDone) continue;
			$atnum = $block->atnum;
			$block_name = $block->name;
			foreach ($block->lines as $line) {
				if($line->hasDone)
					continue;
				$line_name = $line->name;
				break;
			}
		}
		
		echo $block_name . ' - ' . $line_name . "<br>" . $eol;
		
		$segs = ['akta', 'positivitet', 'relevans', 'tillit', 'balans', 'omdome', 'motivation', 'goal', 'genomforande' ];

		foreach($segs as $key => $entry)
		{
			echo '  ' . $entry . ' : ';

			$query = "SELECT * FROM surv WHERE pers='" . $pid . "'" . " AND type=7" .
					 " AND name='" . $entry . "' AND seq='" . 1 . "'";
			$sid = 0;
			$res = mysqli_query($emperator, $query);
			if (!$res)
			{
				$err = 'DB Error, query surv --'.$query.'--';
			} else {
				$prow = mysqli_fetch_array($res);
				if (!$prow) {
					$err = 'DB Error, fetch surv --'.$query.'--';
				} else {
					$sid = $prow['surv_id'];
				}
			}
			
			$query = "SELECT * FROM data WHERE pers='" .$pid . "'" . " AND type=7" .
					 " AND surv='" . $sid . "'";
			$res = mysqli_query($emperator, $query);
			$num = 0; $sum = 0;
			if (!$res)
			{
				$err = 'DB Error, query data --'.$query.'--';
			} else {
				
				while (true) {
					$prow = mysqli_fetch_array($res);
					if (!$prow) {
						break;
					} else {
						$num += 1;
						$sum += $prow['value_b'];
					}
				}
			}
			
			if ($num>0)
				echo number_format($sum / $num, 1);
			else
				echo '--';

		}

		echo "<br>" . $eol;
		echo "<a href='show_details.php?pid=" . $pid . "'> Detaljer </a> <br>" . $eol;
		echo " <hr> " . $eol;
		
	}
	
}

all();

?>

</body>
</html>


