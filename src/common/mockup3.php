
<?php

$RETURNTO = 'mockup3';

include_once 'debug.php';
include_once 'head.php';
include_once 'common_php.php';
include_once 'tagOut.php';
include_once 'connect.php';
include_once 'main.js.php';
include_once 'util.php';


echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';

function getpid()
{
	return getparam('pid', 16);
}

?>

<script>

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function set_inner_from(obj, url)
{
    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error, status = ${response.status}`);
            }
            return response.text();
        })
        .then((text) => {
            obj.innerHTML = text;
        })
        .catch((error) => {
            obj.innerHTML = `Error: ${error.message}`;
        });
}

async function mainload()
{

	document.getElementById('dbg').innerHTML = 'enter mainload';

    // seg 1

	await sleep(250);

	let div = document.getElementById('seg1');

	let url = 'ptbl.php?id=' + <?php echo getpid(); ?> ;

	document.getElementById('dbg').innerHTML = url;

    set_inner_from(div, url);

    // seg 2

	await sleep(250);

    div = document.getElementById('seg2');

	url = 'seg2.php?id=' + <?php echo getpid(); ?> ;

	document.getElementById('dbg').innerHTML = url;

    set_inner_from(div, url);

    // seg 3

	await sleep(250);

    div = document.getElementById('seg3');

	url = 'seg3.php?id=' + <?php echo getpid(); ?> ;

	document.getElementById('dbg').innerHTML = url;

    set_inner_from(div, url);

}

</script>

<body onload='mainload()'>

<div id='main'>

<!-- logo -->

<img width='50%' src='logo.png' />

<!-- debug -->

<hr>

<div id='dbg'>

---

</div>

<!-- segment 1 -->

<hr>

<div style='min-height: 200px;' id='seg1'>
</div>

<!-- segment 2 -->

<hr>

<div style='min-height: 500px;' id='seg2'>
</div>

<!-- segment 3 -->

<hr>

<div style='min-height: 300px;' id='seg3'>
</div>

<!-- end -->

</div>

</body>


</html>

