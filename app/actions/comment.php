<?php
include '../bootstrap.php';


$commentText = $_POST['text'];
$toUser = $_POST['to_user'];

$conn->query("INSERT INTO comment (text) VALUES('$commentText');");
$commentId = $conn->connection->insert_id;
$conn->query("INSERT INTO `user_comment` (comment_id, from_user, to_user) VALUES('$commentId', '$loggedInId', '$toUser');");

header('Location: ' . $_SERVER['HTTP_REFERER']);
