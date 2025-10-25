
<?php

echo <<<EOT

	<style>

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

		.shake_green:hover {
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
	
	</style>

EOT;

?>

