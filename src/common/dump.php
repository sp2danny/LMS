<html> <head> <title> dump </title> </head>
<body>

	<hr>
	
	<a href='logclear.php'>
		<button> Clear </button>
	</a>

	<hr>
	
	<pre>

<?php

	$fp = fopen("../common/debug_logs.txt", "r");
	if ($fp) while (true) {
		$buffer = fgets($fp, 4096);
		if (!$buffer) break;
		$buffer = trim($buffer);
		echo $buffer . "\n";
	}
	
?>
	</pre>
</body>
</html>
	
