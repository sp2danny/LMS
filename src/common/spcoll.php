
<?php

// spider collect

include_once 'debug.php';
include_once 'connect.php';
include_once 'common.php';
include_once 'get_gr_val.php';
include_once 'process_cmd.php';


function collect_it($data)
{
	if ($data->grpsk === false)
	{
		$str = "Egenskattning av " . $data->pnr . " (" . $data->pid . ")";
	} else {
		$str = "Gruppskattning av " . $data->pnr . " för " . $data->grpsk;
	}
	return $str;
}

function collect_it_egen($data)
{
	global $emperator;

	$pid = ROD('pers', ['pnr'], [$data->pnr], 'pers_id', false);

	$res = [];

	$res['vg'] = ROD('data', ['pers', 'type'], [$pid, 201], 'value_a', false);

	$res['ms'] = ROD('data', ['pers', 'type'], [$pid, 202], 'value_a', false);

	$res['mot'] = ROD('data', ['pers', 'type'], [$pid, 302], 'value_a', false);

	$res['sam'] = ROD('data', ['pers', 'type'], [$pid, 105], 'value_b', false);

	$res['str'] = ROD('data', ['pers', 'type'], [$pid, 101], 'value_b', false);

	$res['kom'] = ROD('data', ['pers', 'type'], [$pid, 103], 'value_b', false);

	$res['dsk'] = ROD('data', ['pers', 'type'], [$pid, 6], 'value_a', false);

	$res['mal'] = ROD('data', ['pers', 'type'], [$pid, 104], 'value_a', false);

	$res['sty'] = ROD('data', ['pers', 'type'], [$pid, 301], 'value_a', false);

	return $res;
}

function collect_it_grp($data)
{
	//debug_log( var_export($data, true) );
	$res = [];
	$for = ROD('pers', ['pnr'], [$data->grpsk], 'pers_id', false);
	$by = ROD('pers', ['pnr'], [$data->pnr], 'pers_id', false);

	$res['vg'] = get_gr_val($by, $for, 209);

	$res['ms']  = get_gr_val($by, $for, 202);

	$res['utv'] = ROD('data', ['pers', 'type'], [$for, 321], 'value_a', false);
	//$res['utv'] = get_gr_val($by, $for, 321);

	$res['dsk'] = ROD('data', ['pers', 'type'], [$for, 6], 'value_a', false);

	$res['sty'] = ROD('data', ['pers', 'type'], [$for, 301], 'value_a', false);

	return $res;
}

function collect_it_2($data)
{
	if ($data->grpsk === false)
		return collect_it_egen($data);
	else
		return collect_it_grp($data);
}

function tooltip($to, $data)
{

	$rr =  collect_it_2($data);

	if ($data->grpsk === false)
		$txt = ["Värdegrund", "Missionstatement", "Motivation", "Samarbete", "Stress", "Kommunikation", "Disk", "Målsättning", "Styrkor"];
	else
		$txt = ["Värdegrund", "Missionstatement", "Utveckling", "Disk", "Styrkor"];
	$n = count($txt);

	$fn = 0;

	if ($data->grpsk === false) {
		while (true)
		{
			if ($rr['vg'])  ++$fn; else break;
			if ($rr['ms'])  ++$fn; else break;
			if ($rr['mot']) ++$fn; else break;
			if ($rr['sam']) ++$fn; else break;
			if ($rr['str']) ++$fn; else break;
			if ($rr['kom']) ++$fn; else break;
			if ($rr['dsk']) ++$fn; else break;
			if ($rr['mal']) ++$fn; else break;
			if ($rr['sty']) ++$fn; else break;

			break;
		}
	} else {
		while (true)
		{
			if ($rr['vg'])  ++$fn; else break;
			if ($rr['ms'])  ++$fn; else break;
			if ($rr['utv']) ++$fn; else break;
			if ($rr['dsk']) ++$fn; else break;
			if ($rr['sty']) ++$fn; else break;

			break;
		}
	}


	$to->startTag("table");
	for ($i=0; $i<$n; ++$i) {
		$line = "<tr> <td> <img width='30px' src=";
		if ($i <  $fn)  $line .= "'corr.png'";
		if ($i == $fn)  $line .= "'heret.png'";
		if ($i >  $fn)  $line .= "'blank.png'";
		$line .= " />  </td><td> ";
		$line .= $txt[$i];
		$line .= " </td> </tr> ";
		$to->regLine($line);
	}
	$to->stopTag("table");
	$to->scTag("br");
	$to->startTag("div", "style='font-size:9px' ");
	$to->regLine(collect_it($data));
	$to->stopTag("div");

}

function make_data()
{

	$pid = getparam("pid");
	$pnr = getparam("pnr");

	if (($pid === false) && ($pnr === false)) return false;

	global $emperator;

	$data = new Data;

	$prow = false;

	if ($pid)
	{
		$query = "SELECT * FROM pers WHERE pers_id='" . $pid . "'";
		$res = mysqli_query($emperator, $query);
		if ($res) $prow = mysqli_fetch_array($res);
	}
	else if ($pnr)
	{
		$query = "SELECT * FROM pers WHERE pnr='" . $pnr . "'";
		$res = mysqli_query($emperator, $query);
		if ($res) $prow = mysqli_fetch_array($res);
	}

	if (!$prow) return false;

	$data->pid    = $prow['pers_id'];
	$data->pnr    = $prow['pnr'];
	$data->name   = $prow['name'];



	return $data;
}

function index()
{
}

?>

