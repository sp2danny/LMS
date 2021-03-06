

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

