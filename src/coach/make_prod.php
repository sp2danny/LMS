
<html>

<head>
<title> Skapa Ny Produkt </title>

<style>

div {
  margin: 35px;
}

</style>

</head>

<body>

	<div>

	<h3> Skapa ny produkt </h3>

	<form action='make_prod_save.php'>
	
	<label for="ptype"> Typ: </label> <br>
	<input type="number" id="ptype" name="ptype" size="50">
	<br><br><br>

	<label for="title"> Rubrik: </label> <br>
	<input type="text" id="title" name="title" size="50">
	<br><br><br>

	<label for="pdesc"> Beskrivning: </label> <br>
	<textarea id="pdesc" name="pdesc" rows="7" cols="50">
	</textarea>
	<br><br><br>

	<label for="price"> Pris: </label> <br>
	<input type="number" id="price" name="price" size="50">
	<br><br><br>

	<label for="img"> Bild: </label> <br>
	<input type="text" id="img" name="img" size="50">
	<br><br><br>

	<label for="unlocks"> Uppl&aring;sning </label> <br>
	<input type="text" id="unlocks" name="unlocks" size="50">
	<br><br><br>

	<br> <input type="submit" value="Skapa">

	</form>
	
	</div>

</body>

</html>

