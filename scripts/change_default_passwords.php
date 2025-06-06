<?php
include '/var/www/html/app/includes/functions.php';

$password = $argv[1];

$password_hash = getPasswordHash($password);
$mysqli = dbLink();

$sql = "UPDATE users SET password='$password_hash'";

if ($mysqli->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $mysqli->error;
}

$mysqli->close();
?>

