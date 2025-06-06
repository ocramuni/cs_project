<?php
session_start();
include('includes/functions.php');
if (!isLogged()) {
    header("Location: index.php");
}

if(isset($_POST['reset'])) {
    resetSettings();
    header("Location: settings.php");
} elseif (isset($_POST['submit']) &&
    isset($_POST['color'])){
    $settings_error = false;
    $avatar_color = $_POST['color'];
    if(isColorValid($avatar_color)) {
       saveSetting('color', $avatar_color);
    } else {
        $settings_error = true;
    }
} else {
    header("Location: settings.php");
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Marco Giunta">
    <title>Settings check</title>
    <link rel="icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link rel="apple-touch-icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex py-4 bg-body-tertiary">
<main class="w-auto m-auto">
    <?php
    if(!$settings_error){
    ?>
        <div class="card border-success mb-3">
            <svg class="bi card-img-top mt-3 text-success" width="64" height="64" fill="currentColor">
                <use xlink:href="assets/images/bootstrap-icons.svg#check-circle-fill"/>
            </svg>
            <div class="card-body">
                <p class="card-text text-center fs-4 text-nowrap">Settings saved successful</p>
            </div>
        </div>
    <?php
        header( "refresh:2;url=settings.php" );
    } else {
    ?>
        <div class="card border-danger mb-3">
            <svg class="bi card-img-top mt-3 text-danger" width="64" height="64" fill="currentColor">
                <use xlink:href="assets/images/bootstrap-icons.svg#x-circle-fill"/>
            </svg>
            <div class="card-body">
                <p class="card-text text-center fs-4 text-nowrap">Something went wrong. Try again later.</p>
            </div>
        </div>
        <?php
        header( "refresh:2;url=settings.php" );
    }
    ?>
</main>
</body>
</html>