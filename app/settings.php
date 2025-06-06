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
    <title>Settings</title>
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
                ?>
                <li><a href="tickets.php" class="nav-link px-2">Tickets</a></li>
            </ul>
            <?php include 'includes/dropdown.inc.php'; ?>
        </header>
        <form action="settings_check.php" name='form' method="POST">
            <div class="row mb-3">
                <label for="avatarColorInput" class="col-sm-1 col-form-label text-nowrap">Avatar color</label>
                <div class="col-sm-10">
                    <input type="color" class="form-control form-control-color" id="avatarColorInput" value="<?php echo(getAvatarColor()); ?>" title="Choose your color" name="color">
                </div>
            </div>
            <button type="submit" name="submit" formmethod="post" class="btn btn-primary">Save</button>
            <button type="submit" name="reset" formmethod="post" class="btn btn-secondary">Reset</button>
        </form>
    </div>
</main>
</body>
</html>
