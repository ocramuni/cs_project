<?php
session_start();
include('includes/functions.php');

if (!isAdmin()) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Marco Giunta">
    <title>Event create</title>
    <link rel="icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link rel="apple-touch-icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/headers.css" rel="stylesheet">
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<main>
    <div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
            <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                <img class="bi" width="48" height="48" role="img" aria-label="Ticket" alt="Ticket" src="assets/images/ticket.png">
            </a>
        </div>
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="index.php" class="nav-link px-2">Events</a></li>
            <li><a href="users.php" class="nav-link px-2">Users</a></li>
            <li><a href="tickets.php" class="nav-link px-2">Tickets</a></li>
        </ul>
        <?php include 'includes/dropdown.inc.php'; ?>
    </header>
        <form class="row g-3" action="event_create_check.php" name='form' method="POST">
            <div class="col-md-4">
                <label for="selectCategory" class="form-label ">Category</label>
                <select id="selectCategory" class="form-select" required name="category">
                    <option selected value="0">Choose...</option>
                    <?php
                    $categories = getAllCategories();
                    foreach ($categories as $category) {
                        echo '<option value="' . $category['id'] . '">' . $category['description'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-12">
                <label for="inputTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="inputTitle" name="title">
            </div>
            <div class="col-12">
                <label for="textAreaDescription" class="form-label">Description</label>
                <textarea class="form-control" id="textAreaDescription" rows="3" name="description"></textarea>
            </div>
            <div class="col-12">
                <label for="inputLocation" class="form-label">Location</label>
                <input type="text" class="form-control" id="inputLocation" placeholder="1234 Main St" name="location">
            </div>
            <div class="col-12">
                <label for="inputDateTime" class="form-label">Date and time</label>
                <div class="col-md-6">
                    <input type="datetime-local" class="form-control" id="inputDateTime" name="date">
                </div>
            </div>
            <div class="col-1">
                <label for="inputTicket" class="form-label text-nowrap">Available tickets</label>
                <input type="text" class="form-control" id="inputTicket" name="tickets" value="1">
            </div>
            <div class="col-12">
                <button type="submit" name="submit" formmethod="post" class="btn btn-primary">Create event</button>
            </div>
        </form>
    </div>
</body>
</html>