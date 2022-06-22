<!-- inlude head.php -->

<!DOCTYPE html>

<html>
<head>  

<?php

if (glob('index.html')) {
	echo '<link rel="stylesheet" href="common/main-v02.css">' . "\n";
} else {
	echo '<link rel="stylesheet" href="../common/main-v02.css">' . "\n";
	echo '<link rel="stylesheet" href="local.css">' . "\n";
}

?>

