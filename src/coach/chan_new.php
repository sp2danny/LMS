
<!doctype html>

<html>

<head>
	<title> New Channel </title>

	<style>

		div.start {
		  margin: 35px;
		}

	</style>

</head>

<body>

	<div class='start'>
	
		<h1> Skapa Ny Kanal </h1>

		<form action="chan_new_process.php">

			<br />
			<label for='name'> Namn: </label> <br />
			<input type='text' id='name' name='name' /> <br />
			<br /> <br />
			<label for='platser'> Platser: </label> <br />
			<input type='number' id='platser' name='platser' /> <br />
			<br /> <br />
			<label for='dagar'> Dagar: </label> <br />
			<input type='number' id='dagar' name='dagar' /> <br />
			<br /> <br />
			<input type='submit' value='Skapa' /> <br />
			<br /><br />

		</form>
	
	</div>
	
</body>
</html>
