

<?php

include 'head.php';

include 'common.php';

include 'cmdparse.php';

$eol = "\n";

echo "<title> Utbildning </title>" . $eol;

echo <<<EOT

<style>
body{
	margin: 0;
	padding: 0;
	width: 100%;
}
.xbody {
	background-color: #ffffff;
	margin-right: 450px;
	margin-left: 200px;
}
#hellobar-bar {
	font-family: "Open Sans", sans-serif;
	width: 100%;
	margin: 0;
	height: 30px;
	display: table;
	font-size: 17px;
	font-weight: 400;
	padding: .33em .5em;
	-webkit-font-smoothing: antialiased;
	color: #5c5e60;
	position: fixed;
	background-color: white;
	box-shadow: 0 1px 3px 2px rgba(0,0,0,0.15);
}
#hellobar-bar.regular {
	height: 30px;
	font-size: 14px;
	padding: .2em .5em;
}
.hb-content-wrapper {
	text-align: center;
	text-align: center;
	position: relative;
	display: table-cell;
	vertical-align: middle;
}
.hb-content-wrapper p {
	margin-top: 0;
	margin-bottom: 0;
}
.hb-text-wrapper {
	margin-right: .67em;
	display: inline-block;
	line-height: 1.3;
}
.hb-text-wrapper .hb-headline-text {
	font-size: 1em;
	display: inline-block;
	vertical-align: middle;
}
#hellobar-bar .hb-cta {
	display: inline-block;
	vertical-align: middle;
	margin: 5px 0;
	color: #ffffff;
	background-color: #22af73;
	border-color: #22af73
}
.hb-cta-button {
	opacity: 1;
	color: #fff;
	display: block;
	cursor: pointer;
	line-height: 1.5;
	max-width: 22.5em;
	text-align: center;
	position: relative;
	border-radius: 3px;
	white-space: nowrap;
	margin: 1.75em auto 0;
	text-decoration: none;
	padding: 0;
	overflow: hidden;
}
.hb-cta-button .hb-text-holder {
	border-radius: inherit;
	padding: 5px 15px;
}
.hb-close-wrapper {
	display: table-cell;
	width: 1.6em;
}
.hb-close-wrapper .icon-close {
	font-size: 14px;
	top: 15px;
	right: 25px;
	width: 15px;
	height: 15px;
	opacity: .3;
	color: #000;
	cursor: pointer;
	position: absolute;
	text-align: center;
	line-height: 15px;
	z-index: 1000;
	text-decoration: none;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head><body>

<div id="hellobar-bar" class="regular closable">
	<div class="hb-content-wrapper">
		<div class="hb-text-wrapper">
			<div class="hb-headline-text">
				<p><span>Mobilanpassning kommer under 2025</span></p>
			</div>
		</div>
	</div>
	<div class="hb-close-wrapper">
		<a href="javascript:void(0);" class="icon-close" onclick="$('#hellobar-bar').fadeOut()">&#10006;</a>
	</div>
</div>
<div class="xbody">
<br /><br /><br /><br /><br /><br />

<img width=70% src="logo.png"> <br>

<br> L&auml;s igenom v&aring;ra allm&auml;na villkor, och godk&auml;nn nedan <br><br>

<a href='https://www.mind2excellence.se/site/GDPR.pdf'> EULA </a> <br><br>

EOT; 


include_once "getparam.php";
echo "<a href='eula_agree.php?pid=" . getparam("pid") . "'>\n";
?>

<button> Jag godk&auml;nner </button> </a>

</body>
</html>

