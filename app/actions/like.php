<?php
include '../bootstrap.php';

$toUser = (int)$_GET['userId'];

function wasLiked($targetUser = false) {
    global $conn, $loggedInId, $toUser;
    $targetUser = $targetUser ? $targetUser : $toUser;
    $result = $conn->connection->query("SELECT * FROM likes WHERE `from_user` = '$loggedInId' AND `to_user` = '$targetUser'");
    return ($result->num_rows ? true : false);
}

if (wasLiked()) {
    $conn->query("DELETE FROM likes WHERE `from_user` = '$loggedInId' AND `to_user` = '$toUser'");
} else {
    $conn->query("INSERT INTO likes (from_user, to_user) VALUES('$loggedInId', '$toUser');");
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
