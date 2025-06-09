<?php
session_start();
include('includes/functions.php');
if (!isLogged()) {
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
    <title>Tickets</title>
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
                <li><a href="/" class="nav-link px-2">Events</a></li>
                <?php
                if( isAdmin() ){
                ?>
                <li><a href="users.php" class="nav-link px-2">Users</a></li>
                <?php
                }
                ?>
                <li><a href="tickets.php" class="nav-link px-2 link-secondary">Tickets</a></li>
            </ul>
            <?php include 'includes/dropdown.inc.php'; ?>
        </header>
            <?php
            if( isset($_GET['id'])){
                $participation_id = $_GET['id'];
                if(isIntValid($participation_id) && getTicketByParticipationID($participation_id)) {
                ?>
                <div class="w-50 m-auto">
                    <div class="card border-success mb-3">
                        <svg class="bi card-img-top mt-3 text-success" width="64" height="64" fill="currentColor">
                            <use xlink:href="assets/images/bootstrap-icons.svg#check-circle-fill"/>
                        </svg>
                        <div class="card-body">
                            <p class="card-text text-center fs-4 text-nowrap">Ticket download successful</p>
                        </div>
                    </div>
                </div>
                <?php
                } else {
                ?>
                <div class="w-50 m-auto">
                    <div class="card border-danger mb-3">
                        <svg class="bi card-img-top mt-3 text-danger" width="64" height="64" fill="currentColor">
                            <use xlink:href="assets/images/bootstrap-icons.svg#x-circle-fill"/>
                        </svg>
                        <div class="card-body">
                        <p class="card-text text-center fs-4 text-nowrap">Something went wrong. Try again later.</p>
                        </div>
                    </div>
                </div>
                <?php
                }
                header( "refresh:3;url=tickets.php" );
                exit();
            }
            if(isAdmin()) {
                $allTickets = getAllTickets();
                $download_link_class = 'pe-none';
            } else {
                $user_details = isLogged();
                $allTickets = getTicketsByUser($user_details);
                $download_link_class = 'pe-auto';
            }
                if (!empty($allTickets)) {
                    echo '<table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Event</th>
                                    <th scope="col">Event Date</th>';
                    if(isAdmin()) {
                        echo '<th scope="col">User</th>';
                    }
                    echo '<th class="text-center" scope="col">Ticket</th>
                                </tr></thead>
				            <tbody>';
                    foreach ($allTickets as $ticket) {
                        $event_date = date_create($ticket['event_date']);
                        echo '<tr>
                                        <td><a href="event_details.php?id=' . $ticket['event_id'] . '" class="link-underline link-underline-opacity-0">' . $ticket['title'] . ' </a></td>
                                        <td>' . date_format($event_date, 'Y-m-d H:i') . ' </td>';
                                        if(isAdmin()) {
                                            echo '<td>' . $ticket['first_name'] . ' ' . $ticket['last_name'] . ' (' . $ticket['email'] . ')</td>';
                                        }
                                        echo'<td class="text-center"><a href="tickets.php?id=' . $ticket['id'] . '" class="' . $download_link_class . '">
                                                <i class="bi bi-download" style="font-size: 2rem; color: #0d6efd;"></i>
                                            </a></td></tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-info" role="alert">
                <div class="fs-2 alert-info">
                <i class="bi bi-ticket me-3"></i>
                No tickets present
                </div>
				  </div>';
                }
?>
    </div>
</main>
</body>
</html>