<?php
session_start();
include('includes/functions.php');

if (!isLogged()) {
    header("Location: index.php");
}

if(isset($_POST['participate']) && isset($_POST['event_id'])){
    $event_id = $_POST['event_id'];
} else {
    header("Location: index.php");
}

include_once("includes/MyEvent.class.php");
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Marco Giunta">
    <title>Event draw check</title>
    <link rel="icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link rel="apple-touch-icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex py-4 bg-body-tertiary">
<main class="w-auto m-auto">
    <?php
    if($event_id != ""){
        $validate_error_msg = '';
        if (!isIntValid($event_id) || $event_id == "0") {
            $validate_error_msg = "Invalid event";
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
            header( "refresh:3;url=index.php");
            exit();
        }
        $user_details = isLogged();
        $myEvent = new MyEvent();
        $event_details = $myEvent->getById($event_id);
        if(!empty($event_details)) {
            $result = setParticipation($event_id, $user_details['id']);
            if ($result) {
                ?>
                <div class="card border-success mb-3">
                    <svg class="bi card-img-top mt-3 text-success" width="64" height="64" fill="currentColor">
                        <use xlink:href="assets/images/bootstrap-icons.svg#check-circle-fill"/>
                    </svg>
                    <div class="card-body">
                        <p class="card-text text-center fs-4">Participation saved successful</p>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <div class="card border-danger mb-3">
                    <svg class="bi card-img-top mt-3 text-danger" width="64" height="64" fill="currentColor">
                        <use xlink:href="assets/images/bootstrap-icons.svg#x-circle-fill"/>
                    </svg>
                    <div class="card-body">
                        <p class="card-text text-center fs-4 text-nowrap">There was an error saving participation. Please try again.</p>
                    </div>
                </div>
                <?php
            }
            header( "refresh:2;url=event_details.php?id=".$event_id );
            exit();
        }
    }
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
    header( "refresh:2;url=event_details.php?id=".$event_id );
    ?>
</main>
</body>
</html>