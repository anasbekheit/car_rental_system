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

if (isset($_GET['value'])) {
    if($_GET['value'] == 'proceed_to_checkout'){
        $_SESSION['reservation'] = "Reservation in progress";
        header('location: checkout.php');
    }
    elseif ($_GET['value'] == 'back'){
        unset($_SESSION['car']);
        if (isset($_SESSION['reservation']))
        {unset($_SESSION['reservation']);}
        header('location: ../view/search.html');
    }
    else{
        throw new Error("Undefined value ${$_GET['value']}");
    }
}