<?php
session_start();
include('includes/functions.php');
if (!isAdmin()) {
    header("Location: index.php");
}
if(isset($_POST['submit']) &&
    isset($_POST['date']) &&
    isset($_POST['category']) &&
    isset($_POST['location']) &&
    isset($_POST['tickets']) &&
    isset($_POST['title']) &&
    isset($_POST['description'])){
    $event_date = $_POST['date'];
    $category_id = $_POST['category'];
    $location = $_POST['location'];
    $tickets = $_POST['tickets'];
    $title = $_POST['title'];
    $description = $_POST['description'];
} else {
    header("Location: event_create.php");
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
    <title>Event create check</title>
    <link rel="icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link rel="apple-touch-icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex py-4 bg-body-tertiary">
<main class="w-auto m-auto">
    <?php
    if($event_date != "" && $category_id != "" && $description != ""  && $title != "" && $tickets != "" && $location != ""){
        $validate_error_msg = '';
        if (!isStringValid($location) || !isStringValid($description) || !isStringValid($title)) {
            $validate_error_msg = "Only letters and white space allowed";
        } elseif (!isIntValid($tickets) || $tickets == '0') {
            $validate_error_msg = "Invalid number of tickets";
        } elseif (!isIntValid($category_id) || $category_id == "0") {
            $validate_error_msg = "Invalid category";
        } elseif (!isDateValid($event_date)) {
            $validate_error_msg = "Invalid date or time";
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
            header( "refresh:5;url=event_create.php" );
            exit();
        }
        $myEvent = new MyEvent();
            $created = $myEvent->create($event_date, $category_id, $location, $tickets, $description, $title);
            if ($created) {
                ?>
                <div class="card border-success mb-3">
                    <svg class="bi card-img-top mt-3 text-success" width="64" height="64" fill="currentColor">
                        <use xlink:href="assets/images/bootstrap-icons.svg#check-circle-fill"/>
                    </svg>
                    <div class="card-body">
                        <p class="card-text text-center fs-4 text-nowrap">Event created</p>
                    </div>
                </div>
                <?php
                header( "refresh:2;url=index.php" );
            } else {
                ?>
                <div class="card border-danger mb-3">
                    <svg class="bi card-img-top mt-3 text-danger" width="64" height="64" fill="currentColor">
                        <use xlink:href="assets/images/bootstrap-icons.svg#x-circle-fill"/>
                    </svg>
                    <div class="card-body">
                        <p class="card-text text-center fs-4 text-nowrap">There was an error creating event. Please try again.</p>
                    </div>
                </div>
                <?php
                header( "refresh:2;url=event_create.php" );
            }
    }else{
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
        header( "refresh:3;url=event_create.php" );
    }
    ?>
</main>
</body>
</html>