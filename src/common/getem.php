
<!-- inlude getem.php -->

<?php

include_once 'util.php';
include_once 'process_cmd.php';

function getCP($data) {
	global $RETURNTO;
	$cp_site = 'https://www.mind2excellence.se/site/common/' . $RETURNTO . '.php?noside=true';
	if ($data->pid != 0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr != 0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	// <iframe src="some.pdf" style="min-height:100vh;width:100%" frameborder="0"></iframe>
	return ' <iframe src="' . $cp_site . '" style="min-height:100vh;width:100%" frameborder="0" > ';
}

function getSett($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/cp_settings.php';
	//$cp_have = false;
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return ' <iframe src="' . $cp_site . '" style="min-height:100vh;width:100%" frameborder="0" > ';
}

function getMin($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/minsida.php';
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

function getUtb($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/personal.php';
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

function getKurs($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/kurser.php';
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

function getGrp($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/mingrupp.php';
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

function getMentor($data) {
	$cp_site = 'https://www.mind2excellence.se/site/common/mentor.php';
	if ($data->pid!=0) {
		$cp_site = addKV($cp_site, 'pid', $data->pid);
	}
	if ($data->pnr!=0) {
		$cp_site = addKV($cp_site, 'pnr', $data->pnr);
	}
	return $cp_site ;
}

?>

