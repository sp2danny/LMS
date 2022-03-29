
<!-- inlude common.php -->

<script>
function corr1(idnum, corrval) {
  var elem = document.getElementById('QI-' + idnum);
  var sel = document.querySelector('input[name="' + idnum + '"]:checked');
  if (sel === null) {
    elem.src = '../common/blank.png';
    return false;
  } else {
    var ok = (sel.value == corrval);
    elem.src = ok ? "../common/corr.png" : "../common/err.png";
    return ok;
  }
}

function showTime() {
  var t1 = document.getElementById("TimeStart").value;
  //var t2 = document.getElementById("TimeStop").value;
  var t3 = document.getElementById("TimeMax").value;
  //if (t2 == "")
  var t2 = (new Date()).getTime().toString();
  var diff = parseInt(t2) - parseInt(t1);
  var rem = parseFloat(t3) - (diff/1000.0);
  rem = Math.round(rem * 10) / 10;

  if (rem < 0) {
    document.getElementById("TimerDisplay").innerHTML = 'Tiden Slut';
  } else {
    st = rem.toString();
    if (!st.includes("."))
        st = st + '.0';
    document.getElementById("TimerDisplay").innerHTML = 'Tid: ' + st + ' s';
  }
}

function doShow() {
  document.getElementById('QueryBox').style.display = "block";
  document.getElementById('StartBtn').style.display = "none";
  document.getElementById("AudioBox").play();
  document.getElementById("TimeStart").value = (new Date()).getTime().toString();

  setInterval(showTime, 150);
}
</script>

<style type="text/css">
  .btndiv {
    display: inline;
  }
</style>

<?php

include 'getparam.php';
include 'convert.php';

?> 
