
<!DOCTYPE html>

<html>
<head> <title> Index </title> 

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body {
  background-color: #ffffff;
  
  margin-top:   50px;
  margin-right: 450px;
  margin-left:  200px;
}

table, th, td {
  border: 0px;
  margin-top: 2px;
}

.hide { display: none; }

.show { display: block; }

.sidenav {
  height: 100%;
  width: 250px;
  position: fixed;
  z-index: 1;
  top: 0;
  right: 0;
  background-color: #F1F2F6;
  overflow-x: hidden;
  padding-top: 20px;
  color: black;
  font-size: 22px;

  background-image: url("side.png");
  background-repeat: repeat-y;
}

.indent {
  margin-left: 60px;
}

.main {
  /*margin-left: 160px;  Same as the width of the sidenav */
  font-size: 28px; /* Increased text to enable scrolling */
  padding: 0px 10px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
</style>

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

</head>
<body>

<div class="sidenav">
  <div class="indent">
    Daniel Nystr&ouml;m <br /> <br />
    Fas 1  <br /> <br />
    176 po&auml;ng <br /> <br />
  </div>
</div>

<div class="main">
  <br /> 
  <img width=50%  src="../logo.png" /> <br />
  <br /> <br /> 
  <h2>Så här påverkar ditt DNA din hjärnas begränsningar</h2>
  <h3>Du skall i denna kurs lära dig varför du behöver uppgradera din hjärnas operativsystem i syfte att maximera din egen potential och nå dina mål.</h3>
  <br /> <br /> 
  <iframe width="1280" height="720" src="https://player.vimeo.com/video/597209255"  frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
  <br /> <br /> 
  <h3>Svara nu på några frågor om denna film</h3>
  <br /> 
  <button id="StartBtn" onclick="doShow()"> Starta </button> <br />
  <div id="QueryBox" style="display:none;" >
    <audio id="AudioBox" preload loop> <source src=" lugn.mp3" type="audio/mp3"></audio>
    <form action=" score.php" method="GET" >
      <input type="hidden" value="1" id="seg" name="seg" />
      <input type="hidden" value="721106" id="pnr" name="pnr" />
      <table>
        <tr height="25px"> <td colspan="2"> <B>Fråga 1 : Tog forntidsmänniskan mer än 10.000 steg varje dag för att få mat? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-1" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-1" name="1" value="1" />Ja
            <br />
            <input type="radio" id="QR-1" name="1" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 2 : Påverkas fokusförmågan av mycket rörelse varje dag? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-2" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-2" name="2" value="1" />Ja
            <br />
            <input type="radio" id="QR-2" name="2" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 3 : Påverkas minnet av du rör på dig varje dag? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-3" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-3" name="3" value="1" />Ja
            <br />
            <input type="radio" id="QR-3" name="3" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 4 : Överlevde forntidsmänniskan i ensamhet? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-4" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-4" name="4" value="1" />Ja
            <br />
            <input type="radio" id="QR-4" name="4" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 5 : Påverkas din stressnivå av många fler intryck varje dag? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-5" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-5" name="5" value="1" />Ja
            <br />
            <input type="radio" id="QR-5" name="5" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 6 : Mår vi bra av att samarbeta varje dag? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-6" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-6" name="6" value="1" />Ja
            <br />
            <input type="radio" id="QR-6" name="6" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 7 : Kan hjärnan tränas på samma vis som en muskel? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-7" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-7" name="7" value="1" />Ja
            <br />
            <input type="radio" id="QR-7" name="7" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 8 : Kan hjärnan hantera fler än 5 djupa relarioner samtidigt? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-8" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-8" name="8" value="1" />Ja
            <br />
            <input type="radio" id="QR-8" name="8" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 9 : Samarbetar vi bäst i grupper över 15 personer? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-9" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-9" name="9" value="1" />Ja
            <br />
            <input type="radio" id="QR-9" name="9" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr height="25px"> <td colspan="2"> <B>Fråga 10 : Är hjärnans viktigaste drivkraft överlevnad? </B> </td> </tr>
        <tr height="45px">
          <td width="45px" > <img id="QI-10" src="blank.png" /> </td>
          <td>
            <input type="radio" id="QR-10" name="10" value="1" />Ja
            <br />
            <input type="radio" id="QR-10" name="10" value="2" />Nej
          </td>
        </tr>
        <tr><td> &nbsp; </td></tr>
        <tr> <td colspan="2">  </td> </tr>
      </table>
      <script>
        function  doCorr() {
          corr1(1, 1);
          corr1(2, 1);
          corr1(3, 2);
          corr1(4, 2);
          corr1(5, 1);
          corr1(6, 1);
          corr1(7, 1);
          corr1(8, 1);
          corr1(9, 2);
          corr1(10, 1);
          document.getElementById("SubmitBtn").style.display = "block";
          document.getElementById("CorrBtn").style.display = "none";
        }
      </script>
      <input id="SubmitBtn" type="submit" value="Klar" style="display:none;" /> <br />
    </form>
    <button id="CorrBtn" onclick="doCorr()"> R&auml;tta </button> <br />
  </div>
  <br /> <br /> <br />

</div>
</body>
</html>
