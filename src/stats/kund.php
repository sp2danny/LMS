
<html>

<head>

	<title> Kundlista </title>

</head>

<style>

	body {
		margin-top:     50px;
		margin-bottom:  50px;
		margin-right:  150px;
		margin-left:    80px;
	}

	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
			
		padding-top:     6px;
		padding-left:   20px;
		padding-right:  20px;
		padding-bottom:  6px;
	}

	th {
		text-align: left;
		background-color: #fff;
	}

	tbody, tr:nth-child(odd) {
		background-color: #fee;
	}
	tbody, tr:nth-child(even) {
		background-color: #eef;
	}


</style>

<body>

	<table>

<?php

$kund = [];

$kund[] = [ "kent.eliasson@arch.se",             "Kent",       "Eliasson",       "+46735669440",  295 ];
$kund[] = [ "santessondoina@gmail.com",          "Doina",      "Santesson",      "+46762850475",  294 ];
$kund[] = [ "jier59@hotmail.com",                "Sven Jimmy", "Ernström",       "+46703159983",  295 ];
$kund[] = [ "claes.mittjas@hotmail.com",         "Claes",      "Mittjas",        "+46722420073",  147 ];
$kund[] = [ "lars@tsse.se",                      "Lars",       "Falkenius",      "+46735363606",  295 ];
$kund[] = [ "jessicaljohansson92@gmail.com",     "Jessica",    "Johansson",      "+46702854609",  294 ];
$kund[] = [ "halina.bartoszek@yahoo.com",        "Halina",     "Bartoszek",      "+46704953930",  147 ];
$kund[] = [ "roger.bolander@live.com",           "Roger",      "Bolander",       "+46707750545",  147 ];
$kund[] = [ "martina.orlop@gmail.com",           "Martina",    "Orlop",          "+46708559935",  590 ];
$kund[] = [ "thomas@gut.se",                     "Thomas",     "Gut",            "+46704841203",  295 ];
$kund[] = [ "johan.angert@gmail.com",            "Johan",      "Angert",         "+46707420268",  295 ];
$kund[] = [ "christina.hildebrand@hotmail.com",  "Christina",  "Hildebrand",     "+46762040652",  294 ];
$kund[] = [ "hans.engberg@gmail.com",            "Hans",       "Engberg",        "+46735152378",  147 ];
$kund[] = [ "annelidaborn@hotmail.com",          "Annelie",    "EngbergDaborn",  "+46709640972",  147 ];
$kund[] = [ "christina.hildebrand@hotmail.com",  "Christina",  "Hildebrand",     "+46762040652",  294 ];
$kund[] = [ "marie.stenulv@gmail.com",           "Marie",      "Stenulv",        "+46739201725",  295 ];
$kund[] = [ "martina.orlop@gmail.com",           "Martina",    "Orlop",          "+46708559935",  295 ];
$kund[] = [ "christian.landstrom1960@gmail.com", "Christian",  "Landström",      "+46709268706",  294 ];
$kund[] = [ "bjorn@gavert.com",                  "Björn",      "Gävert",         "+46731831500",  295 ];
$kund[] = [ "ewa.saric@hotmail.com",             "Ewa",        "Saric",          "+46704520097",  147 ];
$kund[] = [ "info@erikpalm.com",                 "Erik",       "Palm",           "+46739496720",  147 ];
$kund[] = [ "madeleinh@hotmail.com",             "Madelein",   "Hellström",      "+46707710868",  295 ];
$kund[] = [ "georgios.kontorinis@ifmetall.se",   "Georgios",   "Kontorinis",     "+46706921818",  590 ];
$kund[] = [ "ana.wahlstrom@skansen-akvariet.se", "Ana Karina", "Wahlström",      "+46735437546",  885 ];
$kund[] = [ "eva.holmberg.eh@gmail.com",         "Eva",        "Holmberg",       "+46762100050",  885 ];


foreach ($kund as $k)
{
	echo "\t\t<tr>\n";
	for ($i=0; $i<4; ++$i)
		echo "\t\t\t<td> " . $k[$i] . " </td>\n";

	$p = $k[4];

	echo "\t\t\t<td> " . $p . " :- </td>\n";


	$s = "?";
	if (($p%147)==0)
		$s = $p / 147;
	else if (($p%295)==0)
		$s = $p / 295;
	else
		$s = round($p / 295.0 , 0);

	echo "\t\t\t<td> " . $s . " st. </td>\n";

	echo "\t\t</tr>\n";
}

?>

	</table>

</body>
</html>
