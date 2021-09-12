
<!DOCTYPE html>

<html>
<head> <title> Index </title> 

<?php

if (glob('login.php')) {
	echo '<link rel="stylesheet" href="main-v001.css">' . "\n";
} else {
	echo '<link rel="stylesheet" href="../main-v001.css">' . "\n";
	echo '<link rel="stylesheet" href="local-v001.css">' . "\n";
}

?>

