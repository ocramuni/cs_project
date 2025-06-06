<?php
session_start();
include('includes/functions.php');
include_once("includes/MyUser.class.php");
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Marco Giunta">
    <title>Logout</title>
    <link rel="icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
    <link rel="apple-touch-icon" sizes="512x512" type="image/png" href="assets/images/ticket.png">
</head>
<body>
<main>
<?php
if (isLogged()) {
    $user = new MyUser();
    $user->logout();
}
header("Location: index.php");
exit;

?>

</main>
</body>
</html>