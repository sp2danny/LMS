<html>
	<head>
		<title> Hasse </title>
		<script>
			function doit()
			{
				e1 = document.getElementById('pnr');
				e2 = document.getElementById('srv');
				str = 'https://mind2excellence.se/';
				str += e2.value;
				str += '/php/01-b-register.php';
				str += '?pnr=' + e1.value;
				window.location.href = str;
			}
		</script>
	</head>
	<body>
		<label for='pnr'> Personnummer </label>
		<input type='text' id='pnr' /> <br />
		<label for='srv'> Survey </label>
		<input type='text' id='srv' /> <br />
		<button onclick='doit()' > Go! </button>
	</body>
</html>
		