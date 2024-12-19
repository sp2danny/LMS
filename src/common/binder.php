

<!doctype html>

<html>

<head>

<meta charset="utf-8">
	
<title> Personlig Utbildnings P&auml;rm </title>

<script>

function jump(i)
{
	str = "<embed src='pup/niv";
	str += i.toString();
	str += ".php' />";
	obj = document.getElementById('torepl');
	obj.innerHTML = str;
}


</script>

<style>

button.ilbbaicl {
  font-size: 24px;
  font-weight: bold;
  width: 100%;
  border-radius: 9px;
}

table tr td {
  padding-left:   20px;
  padding-right:  20px;
  padding-top:    1px;
  padding-bottom: 1px;
}


</style>
	
</head>

<body>

<table> <tr>

	<td> 
	<button class='ilbbaicl' onclick='jump(1);' > Niv&aring; 1 </button>
	</td>

	<td> 
	<button class='ilbbaicl' onclick='jump(2);' > Niv&aring; 2 </button>
	</td>

	<td> 
	<button class='ilbbaicl' onclick='jump(3);' > Niv&aring; 3 </button>
	</td>

</tr> </table>

<div id='torepl' >
</div>

</body>
</html>

