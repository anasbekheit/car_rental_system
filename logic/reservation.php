<?php
require_once 'server.php';

$results = array();


if (!isset($_SESSION['user'])) {
    header('location: ../view/login.html');
}
if (!isset($_SESSION['car'])) {
    header('location: ../view/search.html');
}
if (isset($_SESSION['reservation']))
{unset($_SESSION['reservation']);}

if (isset($_POST['user_proceed_to_checkout'])) {

    $_SESSION['reservation'] = "Reservation in progress";
    header('location: checkout.php');
}
if (isset($_POST['back_to_search'])) {

    unset($_SESSION['car']);
    if (isset($_SESSION['reservation']))
    {unset($_SESSION['reservation']);}
    header('location: ../view/search.html');
}
$jsonString = $_SESSION['car'];

$car = json_decode($jsonString, true);
$from_date = date_create($_SESSION['from_date']);
$to_date = date_create($_SESSION['to_date']);
$days = date_diff($from_date, $to_date) ->d;
$total_amount = $days * $car['price_per_day'];
$_SESSION['total_amount'] = $total_amount;
echo json_encode($car);