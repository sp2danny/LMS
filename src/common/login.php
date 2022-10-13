
<?php

include 'head.php';

include 'common.php';

include 'cmdparse.php';

$eol = "\n";

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
EOT;

echo '</head><body>' . $eol;

$dircont = scandir(".");

$batts = array();

foreach ($dircont as $key => $value) {
	if (strlen($value) < 5) continue;
	$a = substr($value, 0, 5);
	if ($a != 'batt-') continue;
	$a = substr($value, 5);
	$batts[] = $a;
}

$dagens = array();
$ord = fopen("ord.txt", "r");
if ($ord)
{
	while (true) {
		$buffer = fgets($ord, 4096);
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) continue;
		$cc = 0;
		for ($idx=0; $idx<$len; ++$idx)
			$cc = $cc ^ ord($buffer[$idx]);
		if ($len != 105 || $cc != 8)
			$dagens[] = $buffer;
	}
}

echo <<<EOT

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<div id="hellobar-bar" class="regular closable">
    <div class="hb-content-wrapper">
        <div class="hb-text-wrapper">
            <div class="hb-headline-text">
                <p><span>Mobilanpassning kommer under 2024</span></p>
            </div>
        </div>
    </div>
    <div class="hb-close-wrapper">
        <a href="javascript:void(0);" class="icon-close" onclick="$('#hellobar-bar').fadeOut()">&#10006;</a>
    </div>
</div>
<div class="xbody">
<br /><br /><br /><br /><br /><br />

EOT; 

$logtxt = @fopen("login.txt", "r");
if (!$logtxt)
	$logtxt = fopen("../login.txt", "r");
if ($logtxt)
{
	while (true) {
		$buffer = fgets($logtxt, 4096);
		if (!$buffer) break;
		$buffer = trim($buffer);
		$len = strlen($buffer);
		if ($len == 0) {
			echo '<br>' . $eol;
			continue;
		}
		if (!$buffer) break;
		$cmd = cmdparse($buffer);
		if ($cmd->is_command) {
			switch ($cmd->command) {
				case "logo":
					echo '<img width=70% src="logo.png"> <br>' . $eol;
					break;
				case "login":
					echo '<form action="' . 'personal.php' . '" method="GET">' . $eol;
					echo '<br><br><label for="pnr">' . $cmd->params[0] . '</label>' . $eol;
					echo '<input type="text" id="pnr" name="pnr"><br>' . $eol;
					break;
				case "start":
					echo '<input type="submit" value="' . $cmd->params[0] . '">' . $eol;
					echo '</form>' . $eol;
					break;
				case "newlink":
					echo '<br><br><a href="nypers.php"> ' . $cmd->params[0] . '</a><br>' . $eol;
					break;
				case "link":
					echo '<br><a href="' . $cmd->params[0] . '"> ' . $cmd->params[1] . '</a><br>' . $eol;
					break;
				case "motd":
				case "ord":
					$n = count($dagens);
					if ($n > 0) {
						$i = rand(0, $n-1);
						echo '<br /><br />' . $eol;
						echo '<center>' . $dagens[$i] . '</center>' . $eol;
					}
					break;
				case "image":
					if (count($cmd->params) == 1)
						echo '<img src="../' . $cmd->params[0] . '"> <br>' . $eol;
					else 
						echo '<img width="' . $cmd->params[0] . '%" src="../' . $cmd->params[1] . '"> <br>' . $eol;
					break;
			}
			
		} else {
			echo $buffer . $eol;
			echo '<br>' . $eol;
		}
	}
}

?> 

</div>
</body>
</html>

