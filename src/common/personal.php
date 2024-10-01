
<?php

include_once 'common.php';
include_once 'util.php';

$url = "utbildning.php";

$pnr = getparam('pnr');
$pid = getparam('pid');

if ($pid !== false) {
	$url = addKV($url, 'pid', $pid);
}
if ($pnr !== false) {
	$url = addKV($url, 'pnr', $pnr);
}

echo "<html><head>\n";
echo "<meta http-equiv='Refresh' content='0; url=$url'>\n";
echo "</head><body></body></html>\n";

