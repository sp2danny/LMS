
<?php

function convert($str)
{
	$str = str_replace('å', '&aring' , $str);
	$str = str_replace('ä', '&auml'  , $str);
	$str = str_replace('ö', '&ouml'  , $str);
	$str = str_replace('Å', '&Aring' , $str);
	$str = str_replace('Ä', '&Auml'  , $str);
	$str = str_replace('Ö', '&Ouml'  , $str);
	return $str;
}

?>

