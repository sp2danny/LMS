
.visitab {
  border: 1px solid black;
  margin-top: 2px;
  border-collapse: collapse;
}


.hide { display: none; }

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
  margin: 0 -6px;
}

body {
    background-color: #ffffff;
    margin-top: 50px;
    margin-right: 450px;
    margin-left: 200px;
    margin-bottom: 75px;
}

table, th, td {
    border: 0px;
    margin-top: 2px;
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
    background-image: url("side.png");
    background-repeat: repeat-y;
}

.indent {
    margin-left: 60px;
}

.main {
    font-size: 28px;
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

