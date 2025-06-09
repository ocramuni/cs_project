<?php
session_start();
include('includes/functions.php');
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Marco Giunta">
    <title>Events</title>
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
                <li><a href="/" class="nav-link px-2 link-secondary">Events</a></li>
                <?php
                if( isAdmin() ){
                ?>
                    <li><a href="users.php" class="nav-link px-2">Users</a></li>
                <?php
                }
            if( !isLogged() ){
            ?>
            </ul>
            <div class="col-md-3 text-end">
                <button onclick="window.location.href='/login.php'" type="button" class="btn btn-outline-primary me-2">Login</button>
                <button onclick="window.location.href='/signup.php'" type="button" class="btn btn-primary">Sign-up</button>
            </div>
                <?php
            } else {
                    ?>
                    <li><a href="tickets.php" class="nav-link px-2">Tickets</a></li>
                    </ul>
                    <?php
                include 'includes/dropdown.inc.php';
            }
            ?>
        </header>

        <?php
        if( isAdmin() ){
        ?>
            <button onclick="window.location.href='event_create.php'" type="button" class="btn btn-primary">Create new event</button>
        <?php
        }
    $allEvents = getAllEvents();
    if(!empty($allEvents)){
        echo '<table class="table">
            <thead>
				<tr>
					<th scope="col">Date</th>
					<th scope="col">Title</th>
					<th scope="col">Category</th>
					<th scope="col">Location</th>
					<th scope="col" class="text-center">Tickets</th>
				</tr>
				</thead>
				<tbody>';
        foreach ($allEvents as $event) {
            $event_category = getEventCategoryById($event['category_id']);
            $date = date_create($event['event_date']);
            echo '<tr>

						<td class="text-nowrap">' . date_format($date, 'l, F j, Y - H:i') . ' </td>
						<td><a class="link-underline link-underline-opacity-0" href="event_details.php?id='. $event['id'] . '">' . $event['title'] . '</a></td>
						<td class="text-capitalize">' . $event_category . ' </td>
						<td>' . $event['location'] . ' </td>
						<td class="text-center">' . $event['tickets'] . ' </td>
					  </tr>' ;
        }
        echo '</tbody></table>';
    }
    else{
        echo '<div class="alert alert-info" role="alert">
                <div class="fs-2 alert-info">
                <i class="bi bi-calendar-event"></i>
                No events scheduled at this time
                </div>
                <p>Check back soon !!!</p>
				  </div>';
    }
?>
    </div>
    </main>
</body>
</html>
