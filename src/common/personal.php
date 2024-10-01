
<?php

include_once 'common.php';
include_once 'util.php';

$url = "utbildning.php";

$pnr = getparam('pnr');
$pid = getparam('pid');
$at  = getparam('at');

if ($pid != "") {
	$url = addKV($url, 'pid', $pid);
}
if ($pnr != "") {
	$url = addKV($url, 'pnr', $pnr);
}
if ($at != "") {
	$url = addKV($url, 'at', $at);
}

echo "<html><head>\n";
echo "<meta http-equiv='Refresh' content='0; url=$url'>\n";
echo "</head><body></body></html>\n";

