<?php

$defaultAppereance = "dark";

$output = "";

$loadingProgress = "";

if (isset($_POST['html_input'])) {
    $loadingProgress = "yes";

    $input = $_POST['html_input'];

    $output = convert($input);
}


function convert($input)
{
    $output = $input;

    $output = str_replace("<", "&lt;", $output);
    $output = str_replace(">", "&gt;", $output);
    $output = str_replace("\n", "<br>", $output);

    return $output;
}

function getElementName($input)
{
    // "/[\w]/"
}

?>
<!-- <!DOCTYPE html>  -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="This is a converter that converts default html into emmet abbreviations">
    <title>HTML To Emmet</title>
    <link rel="stylesheet" href="assets/css/convert.css">
    <script src="https://kit.fontawesome.com/8d8c0ed3fe.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <script lang="js" src="assets/js/main.js"></script>
</head>



<body class="<?= isset($_COOKIE['appearence']) ? $_COOKIE['appearence'] : 'device' ?>" data-baseUrl="">
    <div class="flash-container">
        <div class="flash-center flash-center-success">
            <div class="flash-message">

            </div>
            <div class="flash-cancel">
                <i class="fas fa-times"></i>
            </div>
            <div class="flash-bar flash-bar-success">

            </div>
        </div>
    </div>
    <header>
        <div class="right">
            <img src="assets/icons/darkmode.svg" class="appereance-change dark-change" style="display: none;" alt="">
            <img src="assets/icons/lightmode.svg" class="appereance-change light-change" alt="">
        </div>
        </div>
    </header>
    <div class="center-content">
        <div class="content">
            <h1 class="title">HTML to Emmet Converter</h1>
            <div class="form">
                <textarea name="html_input" id="input" placeholder="Type your html"></textarea>
                <button class="submit" value="Submit">Submit</button>
                <div class="loading-Progress loading-hidden" data-state="<?= $loadingProgress ?>">
                </div>

            </div>
            <div class="output <?= !empty($output) ? 'output-filled' : '' ?>" data-result="">
                <?= $output ?>
            </div>

        </div>
    </div>
</body>

</html>







<?php



?>