
<?php

function convert($str)
{
	$str = str_replace('�', '&aring' , $str);
	$str = str_replace('�', '&auml'  , $str);
	$str = str_replace('�', '&ouml'  , $str);
	$str = str_replace('�', '&Aring' , $str);
	$str = str_replace('�', '&Auml'  , $str);
	$str = str_replace('�', '&Ouml'  , $str);
	return $str;
}

?>

