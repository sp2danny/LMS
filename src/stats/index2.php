
<html>

	<head>
		<title>
			Stats!
		</title>

		<style>
			body {
				margin-top: 50px;
				margin-bottom: 50px;
				margin-right: 150px;
				margin-left: 80px;
			}
		</style>

	</head>

	<body> <div>

		<hr />
		<h1> Full </h1>
		full listning <br>
		<a href="tratt.php"> <button> Go! </button> </a>
		<hr />
		<h1> Variant </h1>
		<form action="tratt.php">
			<label for="tag"> utskicks-nummer </label> <br />
			<input type="number" id="tag" name="tag" /> <br /> <br />
			<input type="submit" value="Go!" /> <br />
		</form>
		<hr />
		<h1> Datum </h1>
		<form action="tratt.php">
			<label for="startdate"> start datum </label> <br />
			<input type="date" id="startdate" name="startdate" /> <br /> <br />
			<label for="stopdate"> slut datum </label> <br />
			<input type="date" id="stopdate" name="stopdate" /> <br /> <br />
			<input type="submit" value="Go!" /> <br />
		</form>

	</div> </body>
</html>

