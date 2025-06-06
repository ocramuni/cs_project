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
    <title>Users</title>
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
                <li><a href="users.php" class="nav-link px-2 link-secondary">Users</a></li>
                <li><a href="tickets.php" class="nav-link px-2">Tickets</a></li>
            </ul>
            <?php include 'includes/dropdown.inc.php'; ?>
        </header>
            <?php
            if(isAdmin()) {
                $allUsers = getAllUsers();
                if (!empty($allUsers)) {
                    echo '<table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Email</th>
                                </tr></thead>
				            <tbody>';
                    foreach ($allUsers as $user) {
                        echo '<tr>
                                        <th scope="row">' . $user['id'] . ' </th>
                                        <td>' . $user['last_name'] . ' </td>
                                        <td>' . $user['first_name'] . ' </td>
                                        <td>' . $user['email'] . ' </td>
                                      </tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-info" role="alert">
                <div class="fs-2 alert-info">
                <i class="bi bi-person me-3"></i>
                No users present
                </div>
				  </div>';
                }
            } else {
                header("Location: index.php");
            }
?>
    </div>
</main>
</body>
</html>