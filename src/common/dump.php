<html> <head> <title> dump </title> </head>
<body>

	<hr>
	
	<table> <tr>
		<td>
			<a href='logclear.php'>
				<button> Clear </button>
			</a>
		</td><td>
			&nbsp; &nbsp; &nbsp;
		</td><td>
			<a href='dump.php'>
				<button> Reload </button>
			</a>
		</td>
	</tr></table>

	<hr>
	
	<pre>

<?php

	$fp = fopen("../common/debug_logs.txt", "r");
	if ($fp) while (true) {
		$buffer = fgets($fp, 4096);
		if (!$buffer) break;
		$buffer = trim($buffer);
		$buffer = htmlspecialchars($buffer);
		echo $buffer . "\n";
	}
	
?>
	</pre>
</body>
</html>
	
