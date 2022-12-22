
<?php

include 'head.php';
include 'common.php';
include 'connect.php';

$pid   = getparam('pid');
$nn    = getparam('nn');
$note  = getparam('note');

$query  = "INSERT INTO data (pers, type, value_a, value_c)";
$query .= "VALUES (" . $pid . ", 20, " . $nn . ", '" . $note . "')";

$result = mysqli_query($emperator, $query);

$eol = "\n";

echo "<meta http-equiv=\"refresh\" content=\"1; url='show_details.php?pid=" . $pid . "'\" /> " . $eol;

?>

</head>
<body></body>
</html>
