
<?php
session_start();
include('includes/functions.php');
if( isset($_GET['id']) && isIntValid($_GET['id'])) {
    $event_id = $_GET['id'];
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
    <title>Event details</title>
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
            }else{
                ?>
                    <li><a href="tickets.php" class="nav-link px-2">Tickets</a></li>
                    </ul>
                <?php
                include 'includes/dropdown.inc.php';;
            }
            ?>
        </header>
        <?php
        $myEvent = new MyEvent();
        $event_details = $myEvent->getById($event_id);
        if(!empty($event_details)){
            $date = date_create($event_details['event_date']);
        ?>

            <div class="card">
                <div class="card-header text-capitalize">
                    <?php echo($event_details['category']); ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo($event_details['title']); ?></h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo($event_details['location']); ?></h6>
                    <p class="card-text"><?php echo($event_details['description']); ?></p>
                    <?php
                    if(wasDrawn($event_details['id'])) {
                        $draw_date = date_create($event_details['draw_date']);
                    ?>
                        <p class="card-text">Draw Date:  <?php echo(date_format($draw_date, 'l, F j, Y - H:i')); ?></p>
                    <?php
                    } elseif (isAdmin()) {
                        $button_status = (count(getParticipantsByEventId($event_id)) == 0) ? 'disabled' : '';
                        ?>
                        <p class="card-text">Tickets available: <?php echo($event_details['tickets']); ?></p>
                    <form action="event_draw_check.php" name='form' method="POST">
                        <input type="hidden" name="event_id" value="<?php echo($event_id) ?>" />
                        <input type="hidden" name="tickets" value="<?php echo($event_details['tickets']) ?>" />
                        <button class="btn btn-primary text-end <?php echo($button_status) ?>" type="submit" name="draw" formmethod="post">Draw</button>
                    </form>
                        <?php
                    } elseif (isLogged()) {
                        $button_status = haveParticipated($event_id) ? 'disabled' : '';
                        ?>
                        <p class="card-text">Tickets available: <?php echo($event_details['tickets']); ?></p>
                    <form action="event_participate_check.php" name='form' method="POST">
                        <input type="hidden" name="event_id" value="<?php echo($event_id) ?>" />
                        <button class="btn btn-primary text-end <?php echo($button_status) ?>" type="submit" name="participate" formmethod="post">Participate</button>
                    </form>
                        <?php
                    } else {
                    ?>
                    <p class="card-text">Tickets available: <?php echo($event_details['tickets']); ?></p>
                        <?php
                    }
                    ?>
                </div>
                <div class="card-footer text-body-secondary">
                    <?php echo(date_format($date, 'Y-m-d H:i')); ?>
                </div>
            </div>
                <?php
            if(isAdmin()){
                $participants = getParticipantsByEventId($event_id);
                if(!empty($participants)){
                    echo '<table class="table caption-top mt-5">
                           <caption>Participant List</caption>
                        <thead><tr>
                            <th scope="col">Last Name</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Email</th>
                        </tr></thead>
                        <tbody>';
                    foreach ($participants as $user) {
                        echo '<tr>
                                <td>' . $user['last_name'] . ' </td>
                                <td>' . $user['first_name'] . ' </td>';
                        if (isWinner($user, $event_id)) {
                            echo '<td>' . $user['email'] . ' <span class="badge text-bg-success">Winner</span> </td>';
                        } else {
                            echo '<td>' . $user['email'] . ' </td>';
                        }
                        echo '</tr>' ;
                    }
                    echo '</tbody></table>';
                }
            }
        } else {
            header("Location: index.php");
        }
        ?>
    </div>
</main>
</body>
</html>