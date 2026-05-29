
<html>

<head>

	<title> Kundlista </title>

</head>

<style>

	body {
		margin-top:     50px;
		margin-bottom:  50px;
		margin-right:  150px;
		margin-left:    80px;
	}

	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
			
		padding-top:     6px;
		padding-left:   20px;
		padding-right:  20px;
		padding-bottom:  6px;
	}

	th {
		text-align: left;
		background-color: #fff;
	}

	tbody, tr:nth-child(odd) {
		background-color: #fee;
	}
	tbody, tr:nth-child(even) {
		background-color: #eef;
	}


</style>

<body>

	<table>

<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

function print_r_reverse($input) {
    $lines = preg_split('#\r?\n#', trim($input));
    if (trim($lines[ 0 ]) != 'Array' && trim($lines[ 0 ] != 'stdClass Object')) {
        // bottomed out to something that isn't an array or object
        if ($input === '') {
            return null;
        }
            
        return $input;
    } else {
        // this is an array or object, lets parse it
        $match = array();
        if (preg_match("/(\s{5,})\(/", $lines[ 1 ], $match)) {
            // this is a tested array/recursive call to this function
            // take a set of spaces off the beginning
            $spaces = $match[ 1 ];
            $spaces_length = strlen($spaces);
            $lines_total = count($lines);
            for ($i = 0; $i < $lines_total; $i++) {
                if (substr($lines[ $i ], 0, $spaces_length) == $spaces) {
                    $lines[ $i ] = substr($lines[ $i ], $spaces_length);
                }
            }
        }
        $is_object = trim($lines[ 0 ]) == 'stdClass Object';
        array_shift($lines); // Array
        array_shift($lines); // (
        array_pop($lines); // )
        $input = implode("\n", $lines);
        $matches = array();
        // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
        preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $input, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        $pos = array();
        $previous_key = '';
        $in_length = strlen($input);
        // store the following in $pos:
        // array with key = key of the parsed array's item
        // value = array(start position in $in, $end position in $in)
        foreach ($matches as $match) {
            $key = $match[ 1 ][ 0 ];
            $start = $match[ 0 ][ 1 ] + strlen($match[ 0 ][ 0 ]);
            $pos[ $key ] = array($start, $in_length);
            if ($previous_key != '') {
                $pos[ $previous_key ][ 1 ] = $match[ 0 ][ 1 ] - 1;
            }
            $previous_key = $key;
        }
        $ret = array();
        foreach ($pos as $key => $where) {
            // recursively see if the parsed out value is an array too
            $ret[ $key ] = print_r_reverse(substr($input, $where[ 0 ], $where[ 1 ] - $where[ 0 ]));
        }
            
        return $is_object ? (object)$ret : $ret;
    }
}


$tot_n = 0;
$tot_p = 0;


$kund = [];

$kund[] = [ "mats_owe@yahoo.se",                 "Mats-Owe",   "Gustafsson Sköld", "+46739647875",  294, "2026-05-05T18:34:49Z" ];
$kund[] = [ "joelthulin@gmail.com",              "Joel",       "Thulin",           "+46705548275",  294, "2026-05-05T16:40:40Z" ];
$kund[] = [ "kent.eliasson@arch.se",             "Kent",       "Eliasson",         "+46735669440",  295, "2026-05-05T07:50:08Z" ];
$kund[] = [ "santessondoina@gmail.com",          "Doina",      "Santesson",        "+46762850475",  294, "2026-05-04T17:57:17Z" ];
$kund[] = [ "jier59@hotmail.com",                "Sven Jimmy", "Ernström",         "+46703159983",  295, "2026-05-04T12:02:43Z" ];
$kund[] = [ "claes.mittjas@hotmail.com",         "Claes",      "Mittjas",          "+46722420073",  147, "0" ];
$kund[] = [ "lars@tsse.se",                      "Lars",       "Falkenius",        "+46735363606",  295, "0" ];
$kund[] = [ "jessicaljohansson92@gmail.com",     "Jessica",    "Johansson",        "+46702854609",  294, "0" ];
$kund[] = [ "halina.bartoszek@yahoo.com",        "Halina",     "Bartoszek",        "+46704953930",  147, "0" ];
$kund[] = [ "roger.bolander@live.com",           "Roger",      "Bolander",         "+46707750545",  147, "0" ];
$kund[] = [ "martina.orlop@gmail.com",           "Martina",    "Orlop",            "+46708559935",  590, "0" ];
$kund[] = [ "thomas@gut.se",                     "Thomas",     "Gut",              "+46704841203",  295, "0" ];
$kund[] = [ "johan.angert@gmail.com",            "Johan",      "Angert",           "+46707420268",  295, "0" ];
$kund[] = [ "christina.hildebrand@hotmail.com",  "Christina",  "Hildebrand",       "+46762040652",  294, "0" ];
$kund[] = [ "hans.engberg@gmail.com",            "Hans",       "Engberg",          "+46735152378",  147, "0" ];
$kund[] = [ "annelidaborn@hotmail.com",          "Annelie",    "EngbergDaborn",    "+46709640972",  147, "0" ];
$kund[] = [ "christina.hildebrand@hotmail.com",  "Christina",  "Hildebrand",       "+46762040652",  294, "0" ];
$kund[] = [ "marie.stenulv@gmail.com",           "Marie",      "Stenulv",          "+46739201725",  295, "0" ];
$kund[] = [ "martina.orlop@gmail.com",           "Martina",    "Orlop",            "+46708559935",  295, "0" ];
$kund[] = [ "christian.landstrom1960@gmail.com", "Christian",  "Landström",        "+46709268706",  294, "0" ];
$kund[] = [ "bjorn@gavert.com",                  "Björn",      "Gävert",           "+46731831500",  295, "0" ];
$kund[] = [ "ewa.saric@hotmail.com",             "Ewa",        "Saric",            "+46704520097",  147, "0" ];
$kund[] = [ "info@erikpalm.com",                 "Erik",       "Palm",             "+46739496720",  147, "0" ];
$kund[] = [ "madeleinh@hotmail.com",             "Madelein",   "Hellström",        "+46707710868",  295, "0" ];
//$kund[] = [ "georgios.kontorinis@ifmetall.se",   "Georgios",   "Kontorinis",       "+46706921818",  590, "0" ];
$kund[] = [ "ana.wahlstrom@skansen-akvariet.se", "Ana Karina", "Wahlström",        "+46735437546",  885, "0" ];
$kund[] = [ "eva.holmberg.eh@gmail.com",         "Eva",        "Holmberg",         "+46762100050",  885, "0" ];

$tot_n = 0;
$tot_p = 0;

foreach ($kund as $k)
{
	echo "\t\t<tr>\n";

	echo "\t\t\t<td> </td>\n";
	echo "\t\t\t<td> </td>\n";


	$em = $k[0];
	$tn = $k[1] . " " . $k[2];
	$ph = $k[3];

	echo "\t\t\t<td> " . $em . " </td>\n";
	echo "\t\t\t<td> " . $tn . " </td>\n";
	echo "\t\t\t<td> " . $ph . " </td>\n";

	$p = $k[4];

	echo "\t\t\t<td> " . $p . " :- </td>\n";

	$tot_p += $p;

	$s = "?";
	if (($p%147)==0)
		$s = $p / 147;
	else if (($p%295)==0)
		$s = $p / 295;
	else
		$s = round($p / 295.0 , 0);

	echo "\t\t\t<td> " . $s . " st. </td>\n";

	$tot_n += $s;


	echo "\t\t</tr>\n";
}

//echo "</table>\n";
//echo "<table>\n";

$pd = 0;

$has_alr = [];

$query = "SELECT * FROM data WHERE type=54 AND value_c='payed'";
$result = mysqli_query($emperator, $query);
if ($result) while ($row = mysqli_fetch_array($result))
{
	++$pd;

	$dd = $row['date'];

	//if ($dd < "2026-05-05 18:00:00") continue;

	//if ($dd == "2026-05-26 06:15:06") continue;

	//if (($dd >= "2026-05-26 06:30:49") && ($dd <= "2026-05-26 06:58:35")) continue;
	
	$id = $row['value_a'];

	if (array_key_exists($id, $has_alr))
		continue;

	//$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, " . $id . ", 'validation')";

	$val = false;

	$ol = "";
	$query2 = "SELECT * FROM data WHERE type=52 AND value_a=$id";
	$result2 = mysqli_query($emperator, $query2);
	if ($result2) while ($row2 = mysqli_fetch_array($result2))
	{
		$ss = $row2['value_c'];
		if ($ss == 'validation') $val = true;
		if (!str_starts_with($ss, "order_lines:")) continue;
		$ol = substr($ss, 12);
	}
	if ($ol == "") continue;
	if (!$val) continue;

	$dta = print_r_reverse($ol);

	if ($dta[0]['name'] != "Theréses Vardagsrum") continue;

	$fn = "";
	$query2 = "SELECT * FROM data WHERE type=58 AND value_a=$id";
	$result2 = mysqli_query($emperator, $query2);
	if ($result2) while ($row2 = mysqli_fetch_array($result2))
	{
		$fn = $row2['value_c'];
	}

	$ph = "";
	$query2 = "SELECT * FROM data WHERE type=59 AND value_a=$id";
	$result2 = mysqli_query($emperator, $query2);
	if ($result2) while ($row2 = mysqli_fetch_array($result2))
	{
		$ph = $row2['value_c'];
	}

	$em = "";
	$query2 = "SELECT * FROM data WHERE type=53 AND value_a=$id";
	$result2 = mysqli_query($emperator, $query2);
	if ($result2) while ($row2 = mysqli_fetch_array($result2))
	{
		$em = $row2['value_c'];
	}

	$ch = "";
	$query2 = "SELECT * FROM data WHERE type=61 AND value_a=$id";
	$result2 = mysqli_query($emperator, $query2);
	if ($result2) while ($row2 = mysqli_fetch_array($result2))
	{
		$ch = $row2['value_b'];
	}

	$ta = $dta[0]['total_amount']/100;
	$qt = $dta[0]['quantity'];
	
	if ($ta < 99) continue;

	$tot_p += $ta;
	$tot_n += $qt;
	
	$has_alr[$id] = true;
	

	switch ($ch)
	{
		case 1:
			$ch = "Tess";
			break;
		case 2:
			$ch = "Email";
			break;
		case 3:
			$ch = "Hasse";
			break;
		default:
			$ch = "ingen";
	}

	echo "\t\t<tr>\n";
	//echo "\t\t\t<td> " . $id . " </td>\n";
	echo "\t\t\t<td> " . $dd . " </td>\n";
	echo "\t\t\t<td> " . $ch . " </td>\n";
	echo "\t\t\t<td> " . $em . " </td>\n";
	echo "\t\t\t<td> " . $fn . " </td>\n";
	echo "\t\t\t<td> " . $ph . " </td>\n";
	//echo "\t\t\t<td> " . $dta[0]['name'] . " </td>\n";
	echo "\t\t\t<td> " . $ta . " :- </td>\n";
	echo "\t\t\t<td> " . $qt . " st </td>\n";


	echo "\t\t</tr>\n";

}

echo "\t\t<tr>\n";
echo "\t\t\t<td colspan=5>   </td>\n";
echo "\t\t\t<td> " . $tot_p . " :- </td>\n";
echo "\t\t\t<td> " . $tot_n . " st. </td>\n";
echo "\t\t</tr>\n";

// echo "</table> <hr> <table>\n";

echo "</table>\n";

//echo $pd;

//$str = "order_lines:" . print_r($_POST['order_lines'], true);
//$query = "INSERT INTO data (type, pers, value_a, value_c) VALUES (52, 0, $id, '$str')";
//$res = mysqli_query( $emperator, $query );




?>

	</table>

</body>
</html>
