
<!-- inlude gap.php -->

<?php

function gap_query($name, $num)
{
	$ret = "";

	$ret .= "<br> g�r gapanalys " . $name . "-" . $num . " h�r <br>";

	return $ret;
}

function gap_display($name, $num)
{
	$ret = "";

	$ret .= "<br> visa gapanalys " . $name . "-" . $num . " resultat h�r <br>";

	return $ret;
}


?>


