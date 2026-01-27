
<!-- inlude debug.php -->

<?php

function arr2str($arr)
{
	$ret = "[";
	$first = true;
	foreach($arr as $val)
	{
		if (!$first) $ret .= ",";

		if (is_array($val))
			$ret .= arr2str($val);
		else
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

	$out = date('Y-m-d H:i:s  ||  ');

	$fn = '';
	$ln = '';

	$arr = debug_backtrace();
	if (array_key_exists(0, $arr)) {
		$aa = $arr[0];
		if (array_key_exists('file', $aa))
			$fn = basename($aa['file']);
		if (array_key_exists('line', $aa))
			$ln = $aa['line'];
	}

	$out .= str_pad($fn, 22) . "  ||  ";

	$out .= str_pad($ln, 4) . "  ||  ";

	$out .= $str;

	file_put_contents("../../site/common/debug_logs.txt", $out . PHP_EOL, FILE_APPEND | LOCK_EX);
}

?>

