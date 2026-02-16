<!-- inlude head.php -->

<!DOCTYPE html>

<html>
<head>  

<!-- Privacy-friendly analytics by Plausible -->
<script async src="https://plausible.io/js/pa-8qaTPp8VIWz57Tw2izi0M.js"></script>
<script>
  window.plausible=window.plausible||function(){(plausible.q=plausible.q||[]).push(arguments)},plausible.init=plausible.init||function(i){plausible.o=i||{}};
  plausible.init()
</script>

<style>

<?php

include "../common/main.css.php";

if (glob('local.css')) {
	include "local.css";
}

/*
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
*/

?>

</style>


