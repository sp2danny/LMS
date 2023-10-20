<!-- inlude mypage.php -->

<?php

include 'head.php';
include 'common.php';
include 'connect.php';
include 'roundup.php';

$eol = "\n";

echo <<<EOT

<style>
table tr td {
  padding-left:   5px;
  padding-right:  5px;
  padding-top:    5px;
  padding-bottom: 5px;
}
</style>

<title> V&auml;lkommen </title>

<link rel="stylesheet" href="../main-v03.css" />
<link rel="icon" href="../../site/common/favicon.ico" />

<style>

	div {
		font-family:    roboto;
	}

	td {
		border-spacing:18px;
	}

	img.lite {
		opacity: 0.5;
	}

	.shake_green {
		animation: shake 1.82s cubic-bezier(.36, .07, .19, .97) both infinite;
		transform: rotate(0);
		backface-visibility: hidden;
		perspective: 1000px;
		background-color: #66d40e;
		color: white;
		text-shadow: 0 4px 4px #000;
		border-radius: 12px;
		padding: 15px 32px;
		text-align: center;
		font-size: 22px;
		margin: 22px 12px;
		float: center;
		width: 850px;
	}

	.shake_green:hover {
		animation: none;
		border-style: outset;
		text-shadow: 0 4px 4px #333;
	}


	@keyframes shake {
		10%,
		90% {
			transform: rotate(-0.25deg);
		}
		20%,
		80% {
			transform: rotate(0.5deg);
		}
		30%,
		50%,
		70% {
			transform: rotate(-1deg);
		}
		40%,
		60% {
			transform: rotate(1deg);
		}
	}
</style>

</head><body>
<div>
<br />
<img width=50%  src="logo.png" /> <br />
<br />
<h1> V&auml;lkommen </h1>
<br />
<img src="hasse.jpg" />
<br />

EOT;

$lnk_u = "personal.php?pnr=" . getparam("pnr");
$lnk_t = "Nu k&ouml;r vi!";

echo " <a href='$lnk_u'> <button class='shake_green' > $lnk_t </button> </a> ";



?>

</div>
</body>
</html>

