<?php
session_start();
include 'app/Database.php';

if (!isset($_SESSION['login'])){
    $_SESSION['login'] = 1;
}

$loggedInId = $_SESSION['login'];

$conn = new Database();

$loggedUser = $conn->query("SELECT * FROM user WHERE id='$loggedInId'")[0];
