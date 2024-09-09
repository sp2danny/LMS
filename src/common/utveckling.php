
<!-- inlude personal.php -->

<?php

include 'head.php';
include 'roundup.php';

echo <<<EOT

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body.nomarg {
    background-color: #ffffff;
    margin-top: 5px;
    margin-right: 5px;
    margin-left: 5px;
    margin-bottom: 5px;
}

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

echo '</head><body class="nomarg" >' . $eol;

// echo '<hr />' . $eol;

function ptbl($prow, $mynt, $score=0)
{
	//global $eol;
	//echo '<table>' . $eol;
	//echo '<tr> <td> Kundnummer    </td> <td> ' . $prow[ 'pers_id' ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> Guldmynt     </td> <td> ' . $mynt   . '</td></tr>' . $eol;
	//echo '<tr> <td> Namn          </td> <td> ' . $prow[ 'name'    ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> Po&auml;ng   </td> <td> ' . $score  . '</td></tr>' . $eol;
	//echo '<tr> <td> Personnummer  </td> <td> ' . $prow[ 'pnr'     ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> </td> <td> </td> </tr>' . $eol;
	//echo '<tr> <td> Medlem sedan  </td> <td> ' . $prow[ 'date'    ] . '</td> <td> &nbsp;&nbsp;&nbsp; </td> <td> </td> <td> </td> </tr>' . $eol;
	//echo '</table>' . $eol;
	//echo '<hr />' . $eol;

}

function addKV($lnk, $k, $v)
{
	if (strpos($lnk, '?')===false)
		return $lnk . '?' . $k . '=' . $v;
	else
		return $lnk . '&' . $k . '=' . $v;
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


	echo "<table>" . $eol;


	if (true) // per
	{
		echo "<tr><td>" . $eol;
		echo "<img src='per.png' /> </td> <td style='width:462px;' > " . $eol;

		echo "<div> " . $eol;

		$cp_site = 'https://mind2excellence.se/site/common/per.php';
		$cp_site = addKV($cp_site, "pid", $pid);
		$cp_site = addKV($cp_site, "pnr", $pnr);

		echo ' <embed type="text/html" src="' . $cp_site . '" width="462px" height="296px" > ' . $eol;
		echo "</div> " . $eol;

		echo '</td><td> ' . $eol;

		echo "<h1> Steg Ett </h1>" . $eol;
		echo "<h3> Positiv, Äkta, Relevant </h3>" . $eol;
		echo "Om man är <pre> Positiv, Äkta, Relevant </pre> så blir man omtyckt <br>" . $eol;
		echo "Det är förutsättningen för att kunna fungera i grupp <br>" . $eol;

		echo '</td></tr> ' . $eol;
	}

	if (true) // at
	{
		echo "<tr><td>" . $eol;
		echo "<img src='gen.png' /> </td> <td style='width:462px;' > " . $eol;

		echo "<div> " . $eol;

		$cp_site = 'https://mind2excellence.se/site/common/at.php';
		$cp_site = addKV($cp_site, "pid", $pid);
		$cp_site = addKV($cp_site, "pnr", $pnr);

		echo ' <embed type="text/html" src="' . $cp_site . '" width="462px" height="296px" > ' . $eol;
		echo "</div> " . $eol;

		echo '</td><td> ' . $eol;

		echo "<h1> Steg Två </h1>" . $eol;
		echo "<h3> Ärlig, Tillitsfull </h3>" . $eol;
		echo "Om man är <pre> Ärlig, Tillitsfull </pre> så får man stabilitet &amp; trygghet <br>" . $eol;
		echo "Det är förutsättningen för att må bra <br>" . $eol;


		echo '</td></tr>' . $eol;
	}

	echo ' </table> ' . $eol;

	echo '<hr />' . $eol;


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


	$utv = fopen("utv.txt", "r");
	if ($utv)
	{
		while (true) {
			$buffer = fgets($utv, 4096);
			if (!$buffer) break;
			echo $buffer . "\n";
		}
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








