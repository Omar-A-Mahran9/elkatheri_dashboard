<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>:: STDY Slider Test :: </title>


    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        window.onload = function() {
    // Your code here
    var $ = jQuery.noConflict();
};

</script>
<?php
    
       echo file_get_contents('https://slider.alkathirimotors.com.sa/RevSliderEmbedderheadIncludes.php');

    ?>    
</head>
<body>
    
<!-- Main Content -->

<section class="intro" style="direction: ltr">
    <?php
        echo file_get_contents('https://slider.alkathirimotors.com.sa/RevSliderEmbedderputRevSlider.php?slide='.$name);
    ?>
</section>

</body>
</html>