
<?php

echo <<<EOT

	<style>

		input[type="checkbox"]{
			width: 18px;
			height: 18px;
			box-shadow: 0 0 0 1pt black;
		}

		.centered-div {
			width: 50%; /* Or any specific width */
			margin-left: auto;
			margin-right: auto;
		}

		div {
			font-family:    roboto;
		}

		td {
			border-spacing:18px;
		}

		img.lite {
			opacity: 0.5;
		}

		form {
			border-style : none;
			background-color: #fff;
		}

		blurb {
		}

		.shake_green {
			animation: shake 1.82s cubic-bezier(.36, .07, .19, .97) both infinite;
			transform: rotate(0);
			backface-visibility: hidden;
			perspective: 1000px;
		
			background-color: #66d40e;

			color: white;
			text-shadow: 0 4px 4px #000;
			border-radius: 12px;
			padding: 15px 32px;
			text-align: center;
			font-size: 22px;
			margin: 22px 12px;
			float: center;
			width: 850px;
		}

		.shake_green_sml {
			animation: shake 1.82s cubic-bezier(.36, .07, .19, .97) both infinite;
			transform: rotate(0);
			backface-visibility: hidden;
			perspective: 250px;
		
			background-color: #66d40e;

			color: white;
			text-shadow: 0 4px 4px #000;
			border-radius: 12px;
			padding: 15px 32px;
			text-align: center;
			font-size: 22px;
			margin: 22px 12px;
			float: center;
			width: 222px;
		}


		.shake_green:hover {
			animation: none;
			border-style: outset;
			text-shadow: 0 4px 4px #333;
		}

		.shake_green_sml:hover {
			animation: none;
			border-style: outset;
			text-shadow: 0 4px 4px #333;
		}

		@keyframes shake {
			10%,
			90% {
				transform: rotate(-0.25deg);
			}
			20%,
			80% {
				transform: rotate(0.5deg);
			}
			30%,
			50%,
			70% {
				transform: rotate(-1deg);
			}
			40%,
			60% {
				transform: rotate(1deg);
			}
		}
  
		th, td {
		  padding-top: 10px;
		  padding-bottom: 10px;
		}
		p.preamble {

		}
		p.postamble {

		}
		
		



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


		/* min / max labels at the edges 
		.range::before, .range::after {
		  font: 12px monospace;
		  content: counter(x);
		  position: absolute;
		  bottom: -2ch;
		}
		*/

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


		body {
			background-color: #ffffff;
			margin-top: 5px;
			margin-right: 5px;
			margin-left: 5px;
			margin-bottom: 5px;
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




		
	
	</style>

EOT;

?>

