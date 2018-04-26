<?php
include '../bootstrap.php';

$name = $conn->connection->real_escape_string($_POST['name']);
$age = $conn->connection->real_escape_string($_POST['age']);
$bio = $conn->connection->real_escape_string($_POST['bio']);
$image = $conn->connection->real_escape_string($_POST['image']);


$conn->query("UPDATE user SET name='$name', age='$age', bio='$bio', image='$image'  WHERE id='$loggedInId'");

header('Location: ' . $_SERVER['HTTP_REFERER']);


