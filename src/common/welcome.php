<!-- inlude mypage.php -->

<?php

include 'head.php';
include 'common.php';

echo <<<EOT

<style>
table tr td {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
}
</style>

</head><body>
<br />
<img width=50%  src="logo.png" /> <br />
<br /> <br />

EOT;

include 'connect.php';
include 'roundup.php';

$eol = "\n";

echo <<<EOT

<br />
<h1> V&auml;lkommen </h1>
<br />
<img src="hasse.jpg" />
<br />

EOT;

echo "<a href='";
echo "mypage.php?pnr=" . getparam("pnr");
echo "'> <button> Nu k&ouml;r vi! </button> </a> <br>";

?>

</body>
</html>

