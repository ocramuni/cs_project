<?php
include('includes/functions.php');

if (isLogged()) {
    header("Location: index.php");
}

if(isset($_POST['submit']) &&
    isset($_POST['email']) &&
    isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
} else {
    header("Location: login.php");
}
include_once("includes/MyUser.class.php");
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Marco Giunta">
    <title>Login check</title>
    <link rel="icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link rel="apple-touch-icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex py-4 bg-body-tertiary">
<main class="w-auto m-auto">
    <?php
    if(isEmailValid($email) && $password != ""){
        $user = new MyUser();
        $logged = $user->login($email, $password);
        if ($logged) {
    ?>
        <div class="card border-success mb-3">
            <svg class="bi card-img-top mt-3 text-success" width="64" height="64" fill="currentColor">
                <use xlink:href="assets/images/bootstrap-icons.svg#check-circle-fill"/>
            </svg>
            <div class="card-body">
                <p class="card-text text-center fs-4 text-nowrap">Login successful</p>
            </div>
        </div>
        <?php
        header( "refresh:3;url=index.php" );
        } else {
    ?>
        <div class="card border-danger mb-3">
            <svg class="bi card-img-top mt-3 text-danger" width="64" height="64" fill="currentColor">
                <use xlink:href="assets/images/bootstrap-icons.svg#x-circle-fill"/>
            </svg>
            <div class="card-body">
                <p class="card-text text-center fs-4 text-nowrap">Incorrect username or password</p>
            </div>
        </div>
        <?php
        header( "refresh:3;url=login.php" );
        }
    }
    else{
    ?>
    <div class="card border-danger mb-3">
        <svg class="bi card-img-top mt-3 text-danger" width="64" height="64" fill="currentColor">
            <use xlink:href="assets/images/bootstrap-icons.svg#x-circle-fill"/>
        </svg>
        <div class="card-body">
            <p class="card-text text-center fs-4 text-nowrap">Please fill in a valid value for all fields</p>
        </div>
    </div>
        <?php
        header( "refresh:3;url=login.php" );
    }
    ?>
</main>
</body>
</html>