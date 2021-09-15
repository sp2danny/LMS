
<script>
function corr1(idnum, corrval) {
  var elem = document.getElementById('QI-' + idnum);
  var sel = document.querySelector('input[name="' + idnum + '"]:checked');
  if (sel === null)
    elem.src = 'blank.png';
  else
    elem.src = (sel.value == corrval) ? "corr.png" : "err.png";
}
function doShow() {
  document.getElementById('QueryBox').style.display = "block";
  document.getElementById('StartBtn').style.display = "none";
  document.getElementById("AudioBox").play();
}
</script>

<?php

include 'getparam.php';
include 'convert.php';

?> 
