<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Floating Overlapping Image with X Button</title>
<style>
  .container {
    position: relative;
    width: 100%;
    max-width: 600px;
    margin: 50px auto;
    font-family: Arial, sans-serif;
  }

  /* Style for the image to float above the text and partially obscure it */
  .floating-image {
    position: absolute;
    top: -50px; /* Adjust to position the image */
    left: 50%;
    transform: translateX(-50%);
    width: 200px; /* Adjust image size as needed */
    z-index: 10;
    border: 2px solid #ccc;
    background-color: white;
    display: inline-block;
  }

  /* Container for the image to position the 'X' button inside */
  .image-container {
    position: relative;
    display: inline-block;
  }

  /* Style the 'X' button inside the image */
  .close-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 255, 255, 0.8);
    border: none;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
    padding: 2px 6px;
    line-height: 1;
    border-radius: 50%;
    color: red;
  }

  /* Style for the text section */
  .text {
    padding-top: 70px; /* Space for the image */
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 20px;
  }
</style>
</head>
<body>

<div class="container">
  <!-- Image floating above the text with an X button -->
  <div class="image-container" id="imageWrapper">
    <button class="close-btn" onclick="discardImage()">X</button>
    <img src="next.png" alt="Floating" class="floating-image" />
  </div>
  <div class="text">
    <h2>Your Text Here</h2>
    <p>This text is partly obscured by the floating image above it, creating an overlapping effect.</p>
    <p>This text is partly obscured by the floating image above it, creating an overlapping effect.</p>
    <p>This text is partly obscured by the floating image above it, creating an overlapping effect.</p>
    <p>This text is partly obscured by the floating image above it, creating an overlapping effect.</p>
    <p>This text is partly obscured by the floating image above it, creating an overlapping effect.</p>
    <p>This text is partly obscured by the floating image above it, creating an overlapping effect.</p>
    <p>This text is partly obscured by the floating image above it, creating an overlapping effect.</p>
    <p>This text is partly obscured by the floating image above it, creating an overlapping effect.</p>
  </div>
</div>

<script>
  function discardImage() {
    const wrapper = document.getElementById('imageWrapper');
    wrapper.style.display = 'none';
    document.querySelector('.text').style.paddingTop = '20px'; // Adjust padding if needed
  }
</script>

</body>
</html>
