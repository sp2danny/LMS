
<!DOCTYPE html>

<html>
<head> <title> Index </title> 

<?php

if (glob('index.html')) {
	echo '<link rel="stylesheet" href="common/main.css">' . "\n";
} else {
	echo '<link rel="stylesheet" href="../common/main.css">' . "\n";
	echo '<link rel="stylesheet" href="local.css">' . "\n";
}

?>
