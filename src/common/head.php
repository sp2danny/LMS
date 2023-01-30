<!-- inlude head.php -->

<!DOCTYPE html>

<html>
<head>  

<?php

if (glob('index.html')) {
	echo '<link rel="stylesheet" href="common/main-v03.css">' . "\n";
	echo '<link rel="icon" href="common/favicon.ico">' . "\n";
} else {
	echo '<link rel="stylesheet" href="../common/main-v03.css">' . "\n";
	if (glob('local.css')) {
		echo '<link rel="stylesheet" href="local.css">' . "\n";
	}
	echo '<link rel="icon" href="../common/favicon.ico">' . "\n";
}

?>

