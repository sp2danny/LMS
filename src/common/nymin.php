



<!-- inlude personal.php -->

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
table.visitab {
  border: 2px solid black;
  margin-top: 2px;
  border-collapse: collapse;
}
td.visitab {
  border: 1px solid grey;
  border-collapse: collapse;
}

.collapsible {
  background-color: #FFF;
  color: black;
  cursor: pointer;
  padding: 8px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.collapsible:hover {
  background-color: #EEE;
}
.content {
  padding: 3px 8px;
  display: none;
  overflow: hidden;
  background-color: white;
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

function ptbl($prow, $mynt, $score=0)
{
	global $eol;
	echo '<table>' . $eol;
	echo '<tr> <td> Kundnummer    </td> <td> ' . $prow[ 'pers_id' ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> Guldmynt     </td> <td> ' . $mynt   . '</td></tr>' . $eol;
	echo '<tr> <td> Namn          </td> <td> ' . $prow[ 'name'    ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> Po&auml;ng   </td> <td> ' . $score  . '</td></tr>' . $eol;
	echo '<tr> <td> Personnummer  </td> <td> ' . $prow[ 'pnr'     ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> </td> <td> </td> </tr>' . $eol;
	echo '<tr> <td> Medlem sedan  </td> <td> ' . $prow[ 'date'    ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> </td> <td> </td> </tr>' . $eol;
	echo '</table>' . $eol;
}

function readini($ini)
{
	$res = [];
	$seg = '';

	while(true) {
		$buffer = fgets($ini, 4096);
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		
		if (str_starts_with($buffer, "#")) continue;

		if (str_starts_with($buffer, "[") && str_ends_with($buffer, "]"))
		{
			$seg = substr($buffer, 1, -1);
			$seg = trim($seg);
			continue;
		}
		
		$p = strpos($buffer, "=");
		if ($p === false) continue;
		
		$key = substr($buffer, 0, $p);
		$key = trim($key);
		$val = substr($buffer, $p+1);
		$val = trim($val);
		
		$res[$seg][$key] = $val;
	}

	return $res;
}

function to_link($alldata, $str)
{
	$p = strpos($str, '.');
	if ($p===false) return "";
	$bat = substr($str, 0, $p);
	$seg = substr($str, $p+1);
	
	foreach ($alldata as $block) {		
		if ($block->battNum != $bat) continue;
		foreach ($block->lines as $line) {
			if ($line->segment != $seg) continue;
			return $line->link;
		}
	}
	return "";
}

function all()
{
	global $emperator, $eol;

	$pnr = getparam('pnr');

	$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";

	$res = mysqli_query($emperator, $query);
	$prow = false;
	$pid = 0;
	$name = '';

	if ($prow = mysqli_fetch_array($res)) {

		$query = 'SELECT * FROM data WHERE pers=' . $prow['pers_id'] . ' AND type=4';
		$res = mysqli_query($emperator, $query);
		$mynt = 0;
		if ($row = mysqli_fetch_array($res))
			$mynt = $row['value_a'];

		ptbl($prow, $mynt);
		$pid = $prow['pers_id'];
		$name = $prow['name'];
	} else {
		echo convert('Denna person hittades inte i databasen.') . " <br />" . $eol;
		return;
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

	$n = count($dagens);
	if ($n > 0) {
		$i = rand(0, $n-1);
		echo '<br /><br />' . $eol;
		echo '<center>' . $dagens[$i] . '</center>' . $eol;
		echo '<br /><br />' . $eol;
	}

	$alldata = roundup($pnr, $pid, $name, true);

	//echo '<table>' . $eol;	
	//foreach ($alldata as $block) {
	//	echo '<tr colspan="3" >' . $eol;
	//	echo '<td> ' . $block->name . '</td>' . $eol;
	//	echo '</tr>' . $eol;		
	//	foreach ($block->lines as $line) {
	//		if ($line->isLink) {
	//			echo '<tr>' . $eol;
	//			echo '<td> ' . $block->battNum . "." . $line->segment . ' </td>' . $eol;
	//			echo '<td> ' . $line->name . ' </td>' . $eol;
	//			echo '<td> ' . $line->link . ' </td>' . $eol;
	//			echo '</tr>' . $eol;
	//		}
	//	}
	//}
	//echo '</table>' . $eol;
	
	$min_file = fopen("min.txt", "r");
	$min_ini = readini($min_file);
	fclose($min_file);

	$cnt = $min_ini['survey']['count'];
	
	for ($i=1; $i<=$cnt; ++$i)
	{
		if ($i!=1)
			echo "<hr>" . $eol;
		
		$key = $i . ".namn";
		echo "Namn : " . $min_ini['survey'][$key] . " <br>" . $eol;

		$key = $i . ".surv";
		$val = $min_ini['survey'][$key];
		//echo "Surv : " . $val . " - " . to_link($alldata, $val) . " <br>" . $eol;
		$lnk = to_link($alldata, $val) . "&returnto=nymin";
		echo "<a href='$lnk'> <button> G&ouml;r Testet </button> </a> <br> " . $eol; 
		
		$key = $i . ".result";
		$val = $min_ini['survey'][$key];
		//echo "Res : " . $val . " - " . to_link($alldata, $val) . " <br>" . $eol;
		$lnk = to_link($alldata, $val) . "&returnto=nymin";
		echo "<a href='$lnk'> <button> Se Resultat </button> </a> <br> ". $eol; 
	}

	echo '<script> ';
	echo ' document.getElementById("CntDiv' . $atnum . '").style.display = "block";';

	echo <<<EOT

var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    //this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>


EOT;

}

all();

?>

</body>
</html>








