

<?php

include 'head.php';

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

function ptbl($prow, $mynt)
{
	global $eol;
	echo '<table>' . $eol;
	echo '<tr> <td> Kundnummer    </td> <td> ' . $prow[ 'pers_id' ] . '</td></tr>' . $eol;
	echo '<tr> <td> Namn          </td> <td> ' . $prow[ 'name'    ] . '</td></tr>' . $eol;
	echo '<tr> <td> Personnummer  </td> <td> ' . $prow[ 'pnr'     ] . '</td></tr>' . $eol;
	echo '<tr> <td> Medlem sedan  </td> <td> ' . $prow[ 'date'    ] . '</td></tr>' . $eol;
	echo '<tr> <td> Guldmynt      </td> <td> ' . $mynt              . '</td></tr>' . $eol;
	echo '</table>' . $eol;
}

function segments($battname)
{
	$styr = fopen('../batt-' . $battname . "/styr.txt", "r");
	if ($styr === false) return false;

	$res = [];
	$curr = '';
	$lineno = 0;
	$maxs = 999;
	while (true) {
		++$lineno;
		$buffer = fgets($styr, 4096); // or break;
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		if ($buffer[0] == '#') continue;
		if ($buffer[0] == '!') {
			$s = substr($buffer, 1);
			$e = explode(' ', $s);
			if ($e[0] == 'max') {
				$maxs = (int)$e[1];
			}
			continue;
		}

		if ( ($buffer[0] == '[') && ($buffer[$len-1] == ']') ) {
			$curr = substr( $buffer, 1, $len-2 );
			$res[$curr] = [];
			continue;
		}

		$res[$curr][] = $buffer;
	}
	fclose($styr);
	return $res;
}

function mklink($batt, $seg, $row)
{
	return '../batt-' . $batt . '/index.php?seg=' . $seg . '&pnr=' . $row['pnr'] . '&pid=' . $row['pers_id'] . '&name='  . $row['name'] ;
}

function all()
{
	global $emperator, $eol;

	$pnr = getparam('pnr');

	$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";

	$res = mysqli_query($emperator, $query);
	$prow = false;
	$pid = 0;

	if ($prow = mysqli_fetch_array($res)) {

		$query = 'SELECT * FROM data WHERE pers=' . $prow['pers_id'] . ' AND type=4';
		$res = mysqli_query($emperator, $query);
		$mynt = 0;
		if ($row = mysqli_fetch_array($res))
			$mynt = $row['value_a'];

		ptbl($prow, $mynt);
		$pid = $prow['pers_id'];
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


	$dircont = scandir("..");

	$batts = array();

	foreach ($dircont as $key => $value) {
		if (strlen($value) < 5) continue;
		$a = substr($value, 0, 5);
		if ($a != 'batt-') continue;
		$a = substr($value, 5);
		$batts[] = $a;
	}

	$allsofar = true;

	$alldata = [];

	class Line {
		public bool $isLink = false;
		public string $link;
		public bool $hasDone;
		public int $segment;
		public string $name;
	}

	class Block {
		public $lines = [];
		public bool $allDone = false;
		public bool $someDone = false;
		public int $battNum;
		public string $name;
	}

	$runnum = 0;
	$atnum = 0;
	//echo '<br /> <br />  ' . $eol;
	foreach ($batts as $key => $value) {
		++$runnum;

		$alldata[$runnum] = new Block;
		$alldata[$runnum]->battNum = $runnum;
		$alldata[$runnum]->name = $value;

		//echo '<button type="button" class="collapsible"> ' . $runnum . '. &nbsp;&nbsp; ' . $value . ' </button> <div class="content" id="CntDiv' . $runnum .'" >';

		$segs = segments($value);
		$done = [];
		for ($i=1; $i<=count($segs); ++$i) {
			$done[$i] = false;
		}

		$query = 'SELECT * FROM data WHERE pers=' . $pid . ' AND type=2 AND value_a=' . ($key+1) ;
		$res = mysqli_query($emperator, $query);
		while ($row = mysqli_fetch_array($res)) {
			$done[$row['value_b']] = true;
		}
		//echo '<ul style="list-style-type:none">';
		for ($i=1; $i<=count($segs); ++$i) {

			$alldata[$runnum]->lines[$i] = new Line;
			$alldata[$runnum]->lines[$i]->segment = $i;
			$alldata[$runnum]->lines[$i]->name = 'Del ' . $i;

			$thisok = false;
			if (array_key_exists($i, $done) && $done[$i])
				$thisok = true;

			$alldata[$runnum]->lines[$i]->hasDone = $thisok;

			$wantlink = false;
			//echo '<li> <img width="12px" height="12px" src="';
			if ($thisok) {
				//echo "corr";
			} else if ($allsofar) {
				//echo "here";
				$allsofar = false;
				$wantlink = true;
				$alldata[$runnum]->lines[$i]->isLink = true;
			} else {
				//echo "blank";
			}
			//echo '.png" > ';
			if ($wantlink) {
				$lnk = mklink($value, $i, $prow);
				$alldata[$runnum]->lines[$i]->link = $lnk;
				//echo '<a href="' . $lnk . '" > ';
				$atnum = $runnum;
				$alldata[$runnum]->someDone = true;
			}
			//echo 'Del ' . $i;
			//if ($wantlink)
			//	echo ' </a> ';
			//'</li>';
		}
		//echo '</ul></div>';
		if ($allsofar)
			$alldata[$runnum]->allDone = true;
	}
	//echo '</ul>';

	//var_dump($alldata);


	foreach ($alldata as $block) {
		echo '<button type="button" class="collapsible"> ' . $block->battNum . '. &nbsp; ';
		echo '<img width="12px" height="12px" src="';
		if ($block->someDone)
			echo 'here';
		else if ($block->allDone)
			echo "corr";
		else
			echo "blank";
		echo '.png" > ';
		echo $block->name . ' </button>';
		echo '<div class="content" id="CntDiv' . $block->battNum .'" >';
		echo '<ul style="list-style-type:none">';
		foreach ($block->lines as $line) {
			echo '<li> <img width="12px" height="12px" src="';
			if ($line->isLink)
				echo 'here';
			else if($line->hasDone)
				echo "corr";
			else
				echo "blank";
			echo '.png" > ';
			if ($line->isLink)
				echo '<a href="' . $line->link . '" > ';
			echo $line->name;
			if ($line->isLink)
				echo ' </a> ';
			echo '</li>';
		}
		echo '</ul></div>';
	}
	echo '</ul>';


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








