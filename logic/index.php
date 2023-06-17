<?php
require_once 'server.php';

if (!isset($_SESSION['user'])) {
    header('location: ../view/login.html');
    exit;
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: ../view/login.html");
    exit;
}
if (isset($_POST['rent_car']))   {
    header("location: ../view/search.html");
    exit;
}
if (isset($_POST['view_reservations'])) {
    header("location: view_reservation.php");
    exit;
}
echo json_encode($_SESSION['user'] ? $_SESSION['user']['fname'] : "");