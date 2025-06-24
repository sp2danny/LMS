<?php

include_once 'connect.php';
include_once 'getparam.php';
include_once 'stapel_disp.php';
include_once 'roundup.php';

$grp = getparam('grp');

?>

<!DOCTYPE html>

<html>
<head>
	<title> Grupp <?php echo $grp; ?> </title>

	<style>
		table tr:nth-child(odd) td {
			background-color: #eee;
		}
		table tr:nth-child(even) td {
			background-color: #ddd;
		}
		th, td {
			padding: 4px;
		}
		th {
			font-weight:bold;
		}
	</style>

</head>
<body>


<?php

function for_discard($str)
{
	//if (!$str) return true;
	if ($str == 'debug') return true;
	if ($str == 'test')  return true;
	if ($str == '')      return true;
	if ($str == 'null')  return true;
	if ($str == null)    return true;
	return false;
}

function is_in($lst, $item)
{
	foreach ($lst as $i)
		if ($item == $i)
			return true;
	return false;
}

function arr_overlap($a1, $a2)
{
	if (for_discard($a1)) return false;
	if (for_discard($a2)) return false;
	$aa1 = explode(",", $a1);
	$aa2 = explode(",", $a2);
	foreach ($aa1 as $b1) {
		if (for_discard($b1)) continue;
		foreach ($aa2 as $b2) {
			if (for_discard($b2)) continue;
			if ($b1==$b2) return true;
		}
	}
	return false;
}

function discdisplay($pid)
{	
	if ($row = data_last("SELECT * FROM data WHERE pers='$pid' AND type='6'")) {
		$LR = $row['value_a'];
		$UD = $row['value_b'];
		return " " . $LR . ", " . $UD . " ";
	} else {
		return " -- inte gjort -- ";
	}
}

function vg($pid)
{
	if ($row = data_last("SELECT * FROM data WHERE pers='$pid' AND type='201'"))
		return $row['value_a'] . "&nbsp;%";
	else
		return "&nbsp;";
}

function ms($pid)
{
	if ($row = data_last("SELECT * FROM data WHERE pers='$pid' AND type='202'"))
		return $row['value_a'] . "&nbsp;%";
	else
		return "&nbsp;";
}

function arr2str($arr)
{
	$str = '[';
	$first = true;
	foreach ($arr as $val)
	{
		if (!$first) $str .= ",";
		$first = false;
		$str .= $val;
	}
	$str .= ']';
	return $str;
}

function dps2str($dps, $is = false)
{
	$str = '';
	$first = true;
	$i = 0;
	foreach ($dps as $dp)
	{
		if (!$first) $str .= " <br> ";
		$first = false;
		if ($is===false)
			$str .= ucfirst(substr($dp->name, 0, 1));
		else
			$str .= $is[$i];
		$str .= " : ";
		$str .= arr2str($dp->vals);
		++$i;
	}
	//$str .= '}';
	return $str;
}

function par($pid)
{
	$args = [];
	$args[] = "PÄR";
	$args[] = "1";
	$args[] = "2";

	$args[] = "positivitet";
	$args[] = "akta";
	$args[] = "relevans";

	$dps = collect_stapel($pid, $args);
	return dps2str($dps, ["P", "Ä", "R"]);
}

function ato($pid)
{
	$args = [];
	$args[] = "ÄTO";
	$args[] = "1";
	$args[] = "2";

	$args[] = "akta";
	$args[] = "tillit";
	$args[] = "omdome";

	$dps = collect_stapel($pid, $args);
	return dps2str($dps, ["Ä", "T", "O"]);
}

function mmg($pid)
{
	$args = [];
	$args[] = "MMG";
	$args[] = "1";
	$args[] = "2";

	$args[] = "motivation";
	$args[] = "goal";
	$args[] = "genomforande";

	$dps = collect_stapel($pid, $args);
	return dps2str($dps, ["M", "M", "G"]);
}

function nojd($pid)
{
	global $emperator;

	$num = 0;
	$sum = 0;
	$query = "SELECT * FROM data WHERE type=16 AND pers=$pid";
	$res = mysqli_query($emperator, $query);
	if ($res) while ($row = mysqli_fetch_array($res))
	{
		$sum += $row['surv'] * 20.0;
		$num += 1;
	}

	if ($num >= 1)
		return number_format($sum / $num, 1) . "&nbsp;%";
	else
		return "n/a";
}

function add1($pid, $tp)
{
	global $emperator;

	$ret = [];

	$query = "SELECT * FROM data WHERE type=$tp AND pers=$pid";
	$res = mysqli_query($emperator, $query);
	if ($res) while($row = mysqli_fetch_array($res)) {
		$val = $row['value_c'];
		if (for_discard($val)) continue;
		if (is_in($ret, $val)) continue;
		$ret[] = $val;
	}
	return $ret;
}

function ssm($pid)
{
	$ret = [];

	$ret['sty'] = add1($pid, 301);
	$ret['mot'] = add1($pid, 302);
	$ret['sva'] = add1($pid, 303);

	return $ret;
}

//	styrkor     301          num (1-5)                 styrka         
//	motivatorer 302          num (1-5)                 motivator         
//	svagheter   303          num (1-5)                 svaghet         

function ant($pid)
{
	global $emperator;
	$ret = [];
	$ret['antal'] = 0;
	$ret['lista'] = [];
	$query = "SELECT * FROM surv WHERE type=209 AND pers=$pid";
	$res = mysqli_query($emperator, $query);
	if ($res) while($row = mysqli_fetch_array($res))
	{
		$ret['antal'] += 1;
		$ret['lista'][] = $row['seq'];
	}
	return $ret;
}

echo "\t<br><br>\n";

echo "\t<table>\n";

echo "\t\t<tr>\n";

echo "\t\t\t<th> Namn </th> <th> Nästa </th> <th> Nöjd </th> <th> Disc </th> <th> Värde<br>grund </th> <th> Mission<br>statement </th> ";
echo " <th> Positiv<br>Äkta<br>Relevant </th> <th> Ärlig<br>Tillitsfull<br>Omdömmesfull </th> <th> Motivation<br>Målsättning<br>Genomförande </th> <th> Styrkor<br>Svagheter<br>Motivatorer </td> \n";

//      positiv     äkta        relevant
//      ärlig       tillitsfull omdömesfull
//      motivation  målsättning genomförande 

echo "\t\t</tr>\n";

$grp = getparam('grp');

$query = "SELECT * FROM pers";
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	$gg = $row["grupp"];
	if (!arr_overlap($grp, $gg)) continue;

	echo "\t\t<tr>\n";

	$for = $row["pers_id"];
	$pnr = $row["pnr"];
	$nam = $row["name"];
	$dsc = discdisplay($for);
	$vg  =  vg($for);
	$ms  =  ms($for);
	$par = par($for);
	$ato = ato($for);
	$mmg = mmg($for);
	$ant = ant($for);

	$vals = collect_stapel_all($for);
	$str = all2str($vals);

	$lst = true;

	if (strlen($str) <= 0) {
		$lst = false;
	}

	$ant = $ant['antal'];

	if ($ant < 2)
		$lst = false;

	$lnk = "plst.php?pid=" . $for;
	$nt = "<a href='$lnk'>";
	$nt .= " " . $nam . " ";
	$nt .= " </a> ";

	$alldata = roundup($pnr, $for, $nam);
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

	$srp = strrpos($block_name, "-");
	if ($srp === false) {
		$pers_at = $block_name . " <br> " . $line_name;
	} else {
		$pers_at  = substr( $block_name, 0, $srp) . " <br> ";
		$pers_at .= substr( $block_name, $srp+1) . " <br> ";
		$pers_at .= $line_name;
	}

// ------

	//$atnum_ll = 0;
	//$block_name_ll = "";
	//$line_name_ll = "";
	//
	//foreach ($alldata as $block) {
	//	// if (!$block->someDone) continue;
	//	$atnum_ll = $block->atnum;
	//	$block_name = $block->name;
	//	foreach ($block->lines as $line) {
	//		if($line->hasDone)
	//			continue;
	//		$line_name = $line->name;
	//		break;
	//	}
	//}
	//
	//$srp = strrpos($block_name, "-");
	//if ($srp === false) {
	//	$pers_at = $block_name . " <br> " . $line_name;
	//} else {
	//	$pers_at  = substr( $block_name, 0, $srp) . " <br> ";
	//	$pers_at .= substr( $block_name, $srp+1) . " <br> ";
	//	$pers_at .= $line_name;
	//}
	//

// ------

	$my_ssm = ssm($for);

	$ssm_str  = "Sty : " . count($my_ssm['sty']) . " <br> ";
	$ssm_str .= "Sva : " . count($my_ssm['sva']) . " <br> ";
	$ssm_str .= "Mot : " . count($my_ssm['mot']) . " <br> ";

	echo "\t\t\t<td> $nt </td> \n";
	echo "\t\t\t<td> $pers_at </td> \n";

	echo "\t\t\t<td> " . nojd($for) . " </td> \n";

	echo "  <td> $dsc </td> <td> $vg </td> <td> $ms </td> ";
	echo " <td> $par </td> <td> $ato </td> <td> $mmg </td> \n";

	echo "\t\t\t<td> $ssm_str </td> \n";

	echo "\t\t</tr>\n";

}

echo "\t</table>\n";

?>

</body>
</html>




