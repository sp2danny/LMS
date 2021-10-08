
<?php

include 'common/head.php';
include 'common/common.php';
include 'common/pulselst.php';

$eol = "\n";
echo '</head><body>' . $eol;

$ps = pulselst(".");
echo '<br /> <br /> <ul> ' . $eol;
foreach ($ps as $key => $value) {
	echo '<li>' . $eol;
	echo $value->name . $eol;
	echo $value->path . $eol;
	echo $value->req . $eol;
	echo '</li>' . $eol;
}
echo '</ul>';

?> 

</body>
</html>


