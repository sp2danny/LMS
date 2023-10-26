

<html>

<head>
<title> Manage Channels </title>

<style>

div.start {
  margin: 35px;
}

.content {
  margin: 5px;
  padding: 0 18px;
  display: none;
  overflow: hidden;
  background-color: #f1f1f1;
}

.collapsible {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.collapsible:hover {
  background-color: #ccc;
}

</style>

<script>

	function collapsible_onclick(obj)
	{
		//obj.classList.toggle("active");
		var content = obj.nextElementSibling;
		if (content.style.display === "block") {
		  content.style.display = "none";
		} else {
		  content.style.display = "block";
		}
	}

</script>

</head>

<body>

	<div class="start" >

	<h1> Manage Channels </h1>
	
	<hr />
	
	<table> <tr>
		<td width=175> <a href='chan_new.php'> <button> Skapa Ny <br /> Kanal </button> </a> </td>
		<!-- <td width=175> <a href='chan_new.php'> <button> Skapa Ny <br /> Kanal </button> </a> </td> -->
	</tr> </table>
	
	<hr />

<?php

include "../site/common/connect.php";
include "../site/common/getparam.php";

$query = "SELECT * FROM data WHERE type='70'";
$res = mysqli_query($emperator, $query);
if ($res) while ($row = mysqli_fetch_array($res))
{
	
	echo "<button class='collapsible' onclick='collapsible_onclick(this);' > ";
	
	echo $row['value_c'];
	
	echo " </button> <div class='content' > ";
	
	echo " - " . " Platser: " . $row['value_a'] . " <br /> \n";
	
	$t = $row['value_b'];
	$tt = $row['date'];
	$now = new DateTime();
	$dt = date_create_from_format("Y-m-d H:i:s",$tt);
	date_add($dt, date_interval_create_from_date_string($t . " days"));
	$df = date_diff($now, $dt);
	$str = $df->days . ' dagar ' . $df->h . ' timmar';
	$cid = $row['data_id'];
	
	echo " - " . " Tid kvar: " . $str . " <br /> \n";
	
	$query2 = "SELECT * FROM data WHERE type='71' AND value_b='$cid'";
	$res2 = mysqli_query($emperator, $query2);
	if ($res2) while ($row2 = mysqli_fetch_array($res2)) {
		echo "Kopplad till variant " . $row2['value_a'];
		echo " (" . $row2['value_c'] . ") <br>\n";
	}

	echo "<table> <tr>\n";
	echo "	<td width=100> <a href='chan_edit.php?cid=$cid'> <button> Redigera   </button> </a> </td>\n";
	echo "	<td width=100> <a href='chan_nvar.php?cid=$cid'> <button> Ny variant </button> </a> </td>\n";
	echo "	<td width=100> <a href='chan_erch.php?cid=$cid'> <button> Ta Bort    </button> </a> </td>\n";
	echo "</tr> </table>\n";

	echo " </div> \n";

}


?>

<hr />
</div>
</body>
</html>
