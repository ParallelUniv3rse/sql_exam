<?php
session_start();

$_SESSION['login'] = (int)$_GET['profileId'];

header('Location: ' . $_SERVER['HTTP_REFERER']);
