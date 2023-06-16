<?php
require_once 'server.php';

if (!isset($_SESSION['user'])) {
    header('location: login.html');
    exit;
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.html");
    exit;
}
if (isset($_POST['rent_car'])) {
    header("location: search.html");
    exit;
}
if (isset($_POST['view_reservations'])) {
    header("location: viewReservation.php");
    exit;
}
include 'index.html';