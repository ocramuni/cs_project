<?php
session_start();
include('includes/functions.php');

if( isLogged() ) {
    header("Location: index.php");
}

if(isset($_POST['submit']) &&
    isset($_POST['first_name']) &&
    isset($_POST['last_name']) &&
    isset($_POST['email']) &&
    isset($_POST['password'])){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}
else{
    header("Location: signup.php");
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
    <title>Sign up check</title>
    <link rel="icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link rel="apple-touch-icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex py-4 bg-body-tertiary">
<main class="w-auto m-auto">
    <?php
    if($first_name != "" && $last_name != "" && $email != "" && $password != "") {
        $validate_error_msg = '';
        $password_validate_msg = isPasswordValid($password);
        if (!isStringValid($first_name) || !(isStringValid($last_name))) {
            $validate_error_msg = "Only letters and white space allowed";
        } elseif (!isEmailValid($email)) {
            $validate_error_msg = "Invalid email address";
        } elseif (!is_null($password_validate_msg)){
            $validate_error_msg = $password_validate_msg;
        }
        if ($validate_error_msg){
            ?>
            <div class="card border-danger mb-3">
                <svg class="bi card-img-top mt-3 text-danger" width="64" height="64" fill="currentColor">
                    <use xlink:href="assets/images/bootstrap-icons.svg#x-circle-fill"/>
                </svg>
                <div class="card-body">
                    <p class="card-text text-center fs-4 text-nowrap"><?php echo($validate_error_msg); ?></p>
                </div>
            </div>
            <?php
            header( "refresh:3;url=signup.php" );
            exit();
        }
        $user = new MyUser();
        if ($user->getDetails($email)) {
    ?>
            <div class="card border-warning mb-3">
            <svg class="bi card-img-top mt-3 text-warning" width="64" height="64" fill="currentColor">
                <use xlink:href="assets/images/bootstrap-icons.svg#exclamation-circle-fill"/>
            </svg>
            <div class="card-body">
                <p class="card-text text-center fs-4">Account already exists. Please use a different email address.</p>
            </div>
        </div>
            <?php
            header( "refresh:3;url=signup.php" );
        } else {
            $created = $user->create($first_name, $last_name, $email, $password);
            if ($created) {
        ?>
                <div class="card border-success mb-3">
                    <svg class="bi card-img-top mt-3 text-success" width="64" height="64" fill="currentColor">
                        <use xlink:href="assets/images/bootstrap-icons.svg#check-circle-fill"/>
                    </svg>
                    <div class="card-body">
                        <p class="card-text text-center fs-4 text-nowrap">Account created</p>
                    </div>
                </div>
        <?php
                 header( "refresh:3;url=login.php" );
            } else {
            ?>
                <div class="card border-danger mb-3">
                    <svg class="bi card-img-top mt-3 text-danger" width="64" height="64" fill="currentColor">
                        <use xlink:href="assets/images/bootstrap-icons.svg#x-circle-fill"/>
                    </svg>
                    <div class="card-body">
                        <p class="card-text text-center fs-4 text-nowrap">There was an error creating your account. Please try again.</p>
                    </div>
                </div>
                    <?php
                    header( "refresh:3;url=signup.php" );
            }
        }
    } else {
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
        header( "refresh:3;url=signup.php" );
    }
    ?>
</main>
</body>
</html>