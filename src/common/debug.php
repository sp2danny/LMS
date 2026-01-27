
<!-- inlude debug.php -->

<?php

function arr2str($arr)
{
	$ret = "[";
	$first = true;
	foreach($arr as $val)
	{
		if (!$first) $ret .= ",";
		$ret .= $val;
		$first = false;
	}
	$ret .= "]";
	return $ret;
}

function debug_log($str)
{
	if (is_array($str))
		$str = arr2str($str);
	file_put_contents("../../site/common/debug_logs.txt", date('Y-m-d H:i:s  ||  ') . $str . PHP_EOL, FILE_APPEND | LOCK_EX);
}

?>

