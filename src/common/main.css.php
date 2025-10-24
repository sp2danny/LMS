

* {
    font-family: "Times New Roman";
}

table, th, td {
  border: 0px;
  margin-top: 2px;
}

table.visitab {
  border: 1px solid black;
  margin-top: 2px;
  border-collapse: collapse;
}

th.visitab {
  border: 1px solid black;
}

td.visitab {
    border: 1px solid black;
}

tr.visitab {
    border: 1px solid black;
}

.hide {
    display: none;
}

.show { display: block; }

.inputslider
{
    width: 450px;
}


.range {
  --ticksThickness: 2px;
  --ticksHeight: 30%;
  --ticksColor: silver;

  display: inline-block;
  background: silver;
  background: linear-gradient(to right, var(--ticksColor) var(--ticksThickness), transparent 1px) repeat-x;
  background-size: calc(100%/((var(--max) - var(--min)) / var(--step)) - .1%) var(--ticksHeight);
  background-position: 0 bottom;
  position: relative;
}


/* min / max labels at the edges */
.range::before, .range::after {
  font: 12px monospace;
  content: counter(x);
  position: absolute;
  bottom: -2ch;
}

.range::before {
  counter-reset: x var(--min);
  transform: translateX(-50%);
}

.range::after {
  counter-reset: x var(--max);
  right: 0;
  transform: translateX(50%);
}


.range > input {
  width: 450px;
  margin: 0 -6px; /* Critical adjustment */
}

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
    background-image: url("https://www.mind2excellence.se/site/common/side.png");
    background-repeat: repeat-y;
}

button.big3 {
	font-size: 14px;
	font-weight: regular;
	width: 155px;
	height: 24px;
	border-radius: 7px;
	background-color: #96BF0D;
	color: black;
}

span.manicon {
    background: url(/site/common/gubbe16.png) no-repeat;
    float: left;
    width: 16px;
    height: 16px;
}

span.husicon {
    background: url(/site/common/hus16.png) no-repeat;
    float: left;
    width: 16px;
    height: 16px;
}

span.nxticon {
    background: url(/site/common/nxt16.png) no-repeat;
    float: left;
    width: 16px;
    height: 16px;
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
    .sidenav {
        padding-top: 15px;
    }

    .sidenav a {
        font-size: 18px;
    }
}

